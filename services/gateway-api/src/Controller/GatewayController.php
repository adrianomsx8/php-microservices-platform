<?php

declare(strict_types=1);


namespace App\Controller;

use App\Application\ForwardUserRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GatewayController
{
    public function __construct(private ForwardUserRequest $forward) {}

    #[Route('/api/login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        return $this->proxy($request, 'POST', '/api/login');
    }

    #[Route('/api/users/register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        return $this->proxy($request, 'POST', '/api/users/register');
    }

    #[Route('/api/profile', methods: ['GET'])]
    public function profile(Request $request): Response
    {
        return $this->proxy($request, 'GET', '/api/profile');
    }

    private function proxy(Request $request, string $method, string $path): Response
    {
        $response = $this->forward->execute($method, $path, $request->getContent(), $request->headers->get('Authorization'));

        return new Response($response->body, $response->status, ['Content-Type' => 'application/json']);
    }
}
