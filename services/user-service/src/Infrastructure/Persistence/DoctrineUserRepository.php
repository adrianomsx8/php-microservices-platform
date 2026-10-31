<?php

declare(strict_types=1);


namespace App\Infrastructure\Persistence;

use App\Domain\User\User;
use App\Domain\User\UserRepository;
use App\Entity\User as UserRecord;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineUserRepository implements UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function save(User $user): int
    {
        $record = new UserRecord();
        $record->setName($user->name);
        $record->setEmail($user->email);
        $record->setPassword($user->passwordHash);
        $record->setCreatedAt($user->createdAt);
        $record->setUpdatedAt($user->createdAt);
        $this->entityManager->persist($record);
        $this->entityManager->flush();

        return $record->getId();
    }
}
