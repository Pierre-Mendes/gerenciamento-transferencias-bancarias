<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Exceptions\LaravelKafkaException;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class TransferKafkaProducer
{
    /**
     *
     * @throws LaravelKafkaException
     */
    public function publish(array $transferData, ?string $key = null, array $headers = []): void
    {
        Log::info('Producer chamado', $transferData);
        try {
            $message = new Message(
                headers: $headers,
                body: $transferData,
                key: $key
            );

            Kafka::publish(env('KAFKA_BROKERS', 'kafka:9092'))
                ->onTopic(env('KAFKA_TOPIC', 'transfers'))
                ->withMessage($message)
                ->send();

            Log::debug('Mensagem publicada no Kafka', [
                'topic' => env('KAFKA_TOPIC', 'transfers'),
                'key' => $key,
                'data' => $transferData
            ]);

        } catch (LaravelKafkaException|\Exception $e) {
            Log::error('Falha ao publicar mensagem', [
                'error' => $e->getMessage(),
                'data' => $transferData
            ]);
            throw $e;
        }
    }

    /**
     *
     * @throws LaravelKafkaException
     */
    public function publishBatch(array $messages): void
    {
        try {
            $producer = Kafka::publish(env('KAFKA_BROKERS', 'kafka:9092'));

            foreach ($messages as $message) {
                $producer->withMessage(new Message(
                    headers: $message['headers'] ?? [],
                    body: $message['data'],
                    key: $message['key'] ?? null
                ));
            }

            $producer->send();

            Log::debug('Lote publicado no Kafka', [
                'topic' => env('KAFKA_BROKERS', 'kafka_topic'),
                'message_count' => count($messages)
            ]);

        } catch (LaravelKafkaException|\Exception $e) {
            Log::error('Falha ao publicar lote', [
                'error' => $e->getMessage(),
                'message_count' => count($messages)
            ]);

            throw $e;
        }
    }
}
