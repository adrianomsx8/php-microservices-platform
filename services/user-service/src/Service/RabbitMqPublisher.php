<?php

namespace App\Service;

use AMQPChannel;
use AMQPConnection;
use AMQPExchange;

class RabbitMqPublisher
{
    public function publish(
        string $event,
        array $payload
    ): void {

        $connection = new AMQPConnection([
            'host' => 'rabbitmq',
            'port' => 5672,
            'login' => 'guest',
            'password' => 'guest',
        ]);

        $connection->connect();

        $channel = new AMQPChannel($connection);

        $exchange = new AMQPExchange($channel);

        $exchange->setName('user.events');
        $exchange->setType(AMQP_EX_TYPE_FANOUT);

        $exchange->declareExchange();

        $exchange->publish(
            json_encode([
                'event' => $event,
                'payload' => $payload,
            ])
        );

        $connection->disconnect();
    }
}