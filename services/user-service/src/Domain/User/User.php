<?php

declare(strict_types=1);


namespace App\Domain\User;

final class User
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly \DateTimeImmutable $createdAt,
    ) {
        if (trim($name) === '' || strlen($name) > 255) {
            throw new \InvalidArgumentException('Nome inv?lido.');
        }
        if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email inv?lido.');
        }
        if ($passwordHash === '') {
            throw new \InvalidArgumentException('Senha obrigat?ria.');
        }
    }
}
