<?php


namespace App\MessageHandler;

use App\Message\UserCreatedMessage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserCreatedMessageHandler
{
    public function __invoke(
        UserCreatedMessage $message
    ): void {

        dump([
            'event' => 'user.created',
            'userId' => $message->getUserId(),
            'email' => $message->getEmail(),
        ]);
    }
}