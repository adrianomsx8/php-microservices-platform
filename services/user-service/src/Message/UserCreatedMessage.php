<?php

namespace App\Message;

class UserCreatedMessage
{
    public function __construct(
        private readonly int $userId,
        private readonly string $email
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}