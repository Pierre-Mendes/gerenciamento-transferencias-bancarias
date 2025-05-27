<?php

namespace App\Console\Commands;

use Carbon\Exceptions\Exception;
use Illuminate\Console\Command;
use Junges\Kafka\Contracts\KafkaMessage;
use Junges\Kafka\Exceptions\ConsumerException;
use Junges\Kafka\Facades\Kafka;

class TestKafkaConsumerCommand extends Command
{
    protected $signature = 'kafka:test-consume';
    protected $description = 'Testa consumo de mensagens do tópico Kafka';

    /**
     * @throws Exception
     * @throws ConsumerException
     */
    public function handle(): void
    {
        Kafka::consumer(['transfers'])
            ->withHandler(function (KafkaMessage $message) {
                dump([
                    'payload' => $message->getBody(),
                    'headers' => $message->getHeaders(),
                    'key' => $message->getKey(),
                ]);
            })
            ->withConsumerGroupId(config('kafka.consumer_group_id', 'banking-group'))
            ->build()
            ->consume();
    }
}
