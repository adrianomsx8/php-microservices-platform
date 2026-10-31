<?php

declare(strict_types=1);


namespace App\Infrastructure\Security;

use App\Application\Port\PasswordHasher;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SymfonyPasswordHasher implements PasswordHasher
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function hash(string $password): string
    {
        return $this->hasher->hashPassword(new User(), $password);
    }
}
