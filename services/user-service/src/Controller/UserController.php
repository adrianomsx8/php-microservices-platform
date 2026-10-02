<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/api/users/register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $user = new User();

        $user->setName($data['name']);
        $user->setEmail($data['email']);

        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                $data['password']
            )
        );

        $user->setCreatedAt(new \DateTimeImmutable());

        $now = new \DateTimeImmutable();

        $user->setCreatedAt($now);
        $user->setUpdatedAt($now);


        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true
        ], 201);
    }
}