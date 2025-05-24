<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\KafkaMessage;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;

class ProcessKafkaTransfers extends Command
{
    protected $signature = 'kafka:consume-transfers 
                            {--timeout=30000 : Tempo máximo de espera por novas mensagens}';

    protected $description = 'Consome transferências do Kafka e processa as operações bancárias.';

    /**
     * @throws \Carbon\Exceptions\Exception
     * @throws ConsumerException
     */
    public function handle(): void
    {
        $this->info('Iniciando consumer do Kafka para o tópico transfers...');
        $this->info('Pressione Ctrl+C para parar o consumer.');

        try {
            $consumer = Kafka::consumer()
                ->subscribe('transfers')
                ->withHandler(function(KafkaMessage $message) {
                    $this->processTransfer($message);
                })
                ->withAutoCommit() // Commita automaticamente as mensagens processadas
                ->withOptions([
                    'auto.offset.reset' => 'earliest',
                    'enable.auto.commit' => 'true',
                ])
                ->build();

            $consumer->consume($this->option('timeout'));

        } catch (Exception $e) {
            Log::error('Erro no consumer Kafka: ' . $e->getMessage());
            $this->error('Erro: ' . $e->getMessage());

            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    protected function processTransfer(KafkaMessage $message): void
    {
        $data = $message->getBody();
        $key = $message->getKey();

        $this->info("Processando transferência ID: {$key}");
        Log::info('Transferência recebida', [
            'key' => $key,
            'body' => $data,
            'headers' => $message->getHeaders(),
        ]);

        try {
            // Exemplo de processamento:
            // 1. Validar a transferência
            // 2. Atualizar saldos
            // 3. Registrar a transação
            // 4. Notificar as partes envolvidas

            $this->info("Transferência ID: {$key} processada com sucesso");

        } catch (Exception $e) {
            Log::error("Falha ao processar transferência {$key}", [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            throw $e;
        }
    }
}
