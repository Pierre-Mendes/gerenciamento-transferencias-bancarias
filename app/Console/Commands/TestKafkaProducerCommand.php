<?php

namespace App\Console\Commands;

use App\Services\TransferKafkaProducer;
use Illuminate\Console\Command;
use Junges\Kafka\Exceptions\LaravelKafkaException;

class TestKafkaProducerCommand extends Command
{
    protected $signature = 'kafka:test-produce';
    protected $description = 'Testa envio de mensagem Kafka';

    /**
     * @throws LaravelKafkaException
     */
    public function handle(TransferKafkaProducer $producer): void
    {
        $data = [
            'id_transferencia' => uniqid('', true),
            'valor' => 1500.45,
            'destinatario' => 'Joãozinho123'
        ];

        $producer->publish($data);

        $this->info('Mensagem publicada no tópico "transfers" com sucesso!');
    }
}
