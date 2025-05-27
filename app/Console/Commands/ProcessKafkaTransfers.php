<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use JsonException;
use Junges\Kafka\Contracts\KafkaMessage;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;
use App\Models\Account;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class ProcessKafkaTransfers extends Command
{
    protected $signature = 'kafka:consume-transfers
                            {--timeout=30000 : Tempo máximo de espera por novas mensagens}';

    protected $description = 'Consome transferências do Kafka e processa as operações bancárias.';

    /**
     * @throws \Carbon\Exceptions\Exception
     * @throws ConsumerException|JsonException
     */
    public function handle(): void
    {
        $this->displayStartupMessage();

        try {
            $consumer = Kafka::consumer()
                ->subscribe('transfers')
                ->withHandler(function($message) {
                    $data = $message->getBody();
                    $transferId = $data['transfer_id'] ?? 'N/A';

                    $this->displayMessageHeader($transferId);

                    DB::transaction(function () use ($data) {
                        Log::info('Mensagem recebida do Kafka', $data);
                        $transfer = Transfer::find($data['transfer_id']);

                        if (!$transfer || $transfer->getAttribute('status') !== 'pending') {
                            return;
                        }

                        $this->processTransferOperation($data, $transfer);
                    });

                    $this->displayTransferDetails($data);
                })
                ->withAutoCommit()
                ->withOptions([
                    'auto.offset.reset' => 'earliest',
                    'enable.auto.commit' => 'true',
                ])
                ->build();

            $consumer->consume($this->option('timeout'));

        } catch (Exception $e) {
            $this->handleError($e);
            throw $e;
        }
    }

    protected function displayStartupMessage(): void
    {
        $this->info('═══════════════════════════════════════════════════');
        $this->info('  INICIANDO CONSUMER KAFKA PARA O TÓPICO TRANSFERS  ');
        $this->info('═══════════════════════════════════════════════════');
        $this->newLine();
        $this->info('Pressione Ctrl+C para parar o consumer.');
        $this->newLine();
    }

    protected function displayMessageHeader(string $transferId): void
    {
        $this->info('┌─────────────────────────────────────────────────────┐');
        $this->info('│ 📦 NOVA MENSAGEM RECEBIDA                          │');
        $this->info('├─────────────────────────────────────────────────────┤');
        $this->info("│ Transfer ID: {$transferId}");
        $this->info('├─────────────────────────────────────────────────────┤');
    }

    protected function displayTransferDetails(array $data): void
    {
        $status = $data['status'] ?? 'N/A';

        $this->info('│ DETALHES DA TRANSFERÊNCIA:                          │');
        $this->info('├─────────────────────────────────────────────────────┤');
        $this->info("│ Tipo:          {$data['type']}");

        if (isset($data['from_account_id'])) {
            $this->info("│ Conta origem:  {$data['from_account_id']}");
        }

        if (isset($data['to_account_id'])) {
            $this->info("│ Conta destino: {$data['to_account_id']}");
        }

        $formattedAmount = number_format($data['amount'], 2, ',', '.');
        $this->info("│ Valor:         R$ {$formattedAmount}");
        $this->info("│ Status:        {$status}");
        $this->info('└─────────────────────────────────────────────────────┘');
        $this->newLine();
    }

    protected function handleError(Exception $e): void
    {
        Log::error('Erro no consumer Kafka: ' . $e->getMessage());
        $this->displayError($e->getMessage());
    }

    protected function displayError(string $message): void
    {
        $this->newLine();
        $this->error('┌─────────────────────────────────────────────────────┐');
        $this->error('│ 🚨 ERRO NO PROCESSAMENTO                            │');
        $this->error('├─────────────────────────────────────────────────────┤');
        $this->error("│ {$message}");
        $this->error('└─────────────────────────────────────────────────────┘');
        $this->newLine();
    }

    protected function processTransferOperation(array $data, Transfer $transfer): void
    {
        $type = $data['type'];
        $amount = (float) $data['amount'];

        switch ($type) {
            case 'deposit':
                $this->processDeposit($data, $transfer, $amount);
                break;

            case 'withdraw':
                $this->processWithdraw($data, $transfer, $amount);
                break;

            case 'transfer':
                $this->processTransferBetweenAccounts($data, $transfer, $amount);
                break;
        }
    }

    protected function processDeposit(array $data, Transfer $transfer, float $amount): void
    {
        $to = Account::find($data['to_account_id']);
        if ($to) {
            $to->setAttribute('balance', $to->getAttribute('balance') + $amount);
            $to->save();
            $this->updateTransferStatus($transfer, 'processed');
            $this->info("│ ✅ Depósito realizado na conta {$data['to_account_id']}");
        } else {
            $this->updateTransferStatus($transfer, 'failed');
            $this->info("│ ❌ Conta destino {$data['to_account_id']} não encontrada");
        }
    }

    protected function processWithdraw(array $data, Transfer $transfer, float $amount): void
    {
        $from = Account::find($data['from_account_id']);

        if (!$from) {
            $this->updateTransferStatus($transfer, 'failed');
            $this->info("│ ❌ Falha no saque: Conta não encontrada");
            return;
        }

        if ($from->getAttribute('balance') < $amount) {
            $this->updateTransferStatus($transfer, 'failed');
            $this->info("│ ❌ Falha no saque: Saldo insuficiente");
            return;
        }

        $from->setAttribute('balance', $from->getAttribute('balance') - $amount);
        $from->save();
        $this->updateTransferStatus($transfer, 'processed');
        $this->info("│ ✅ Saque realizado da conta {$data['from_account_id']}");
    }

    protected function processTransferBetweenAccounts(array $data, Transfer $transfer, float $amount): void
    {
        $from = Account::find($data['from_account_id']);
        $to = Account::find($data['to_account_id']);

        if (!$from) {
            $this->updateTransferStatus($transfer, 'failed');
            $this->info("│ ❌ Falha na transferência: Conta origem não encontrada");
            return;
        }

        if (!$to) {
            $this->updateTransferStatus($transfer, 'failed');
            $this->info("│ ❌ Falha na transferência: Conta destino não encontrada");
            return;
        }

        if ($from->getAttribute('balance') < $amount) {
            $this->updateTransferStatus($transfer, 'failed');
            $this->info("│ ❌ Falha na transferência: Saldo insuficiente");
            return;
        }

        $from->setAttribute('balance', $from->getAttribute('balance') - $amount);
        $to->setAttribute('balance', $to->getAttribute('balance') + $amount);
        $from->save();
        $to->save();
        $this->updateTransferStatus($transfer, 'processed');
        $this->info("│ ✅ Transferência realizada entre contas");
        $this->info("│    De: {$data['from_account_id']} Para: {$data['to_account_id']}");
    }

    protected function updateTransferStatus(Transfer $transfer, string $status): void
    {
        $transfer->setAttribute('status', $status);
        $transfer->setAttribute('processed_at', now());
        $transfer->save();
    }
}
