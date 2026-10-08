<?php

namespace App\Command;

use App\Service\EmailSender;
use AMQPChannel;
use AMQPExchange;
use AMQPConnection;
use AMQPEnvelope;
use AMQPQueue;
use Symfony\Component\Console\Command\Command;

class ConsumeUserCreatedCommand extends Command
{
    protected static $defaultName =
        'app:consume:user-created';

    public function __construct(
        private EmailSender $emailSender
    ) {
        parent::__construct();
    }

    protected function execute($input, $output): int
    {
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

        $queue = new AMQPQueue($channel);

        $queue->setName('email.user.created');

        $queue->declareQueue();

        $queue->bind('user.events');

        $queue->consume(
            function (
                AMQPEnvelope $message,
                AMQPQueue $queue
            ) {
                $data = json_decode(
                    $message->getBody(),
                    true
                );

                if (
                    $data['event']
                    === 'user.created'
                ) {
                    $this->emailSender
                        ->sendWelcome(
                            $data['payload']['email'],
                            $data['payload']['name']
                        );
                }

                $queue->ack(
                    $message->getDeliveryTag()
                );
            }
        );

        return Command::SUCCESS;
    }
}