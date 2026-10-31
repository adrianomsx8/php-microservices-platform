<?php

declare(strict_types=1);


namespace App\Application\Port;

interface PasswordHasher
{
    public function hash(string $password): string;
}
