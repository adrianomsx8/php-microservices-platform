<?php

declare(strict_types=1);


namespace App\Controller;

use App\Application\RegisterUser;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UserController
{
    #[Route('/api/users/register', methods: ['POST'])]
    public function register(Request $request, RegisterUser $registerUser): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            if (!is_array($data)) {
                throw new \InvalidArgumentException('Corpo JSON inv?lido.');
            }
            foreach (['name', 'email', 'password'] as $field) {
                if (!isset($data[$field]) || !is_string($data[$field])) {
                    throw new \InvalidArgumentException('Campos name, email e password s?o obrigat?rios.');
                }
            }
            $registerUser->execute($data['name'], $data['email'], $data['password']);
        } catch (\JsonException | \InvalidArgumentException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(['success' => true], 201);
    }
}
