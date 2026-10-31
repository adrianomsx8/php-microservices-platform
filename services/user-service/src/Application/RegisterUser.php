<?php

declare(strict_types=1);


namespace App\Application;

use App\Application\Port\EventPublisher;
use App\Application\Port\PasswordHasher;
use App\Domain\User\User;
use App\Domain\User\UserRepository;

final class RegisterUser
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $hasher,
        private EventPublisher $events,
    ) {}

    public function execute(string $name, string $email, string $password): void
    {
        if ($password === '') {
            throw new \InvalidArgumentException('Senha obrigat?ria.');
        }

        $user = new User($name, $email, $this->hasher->hash($password), new \DateTimeImmutable());
        $id = $this->users->save($user);
        $this->events->publish('user.created', [
            'id' => $id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
