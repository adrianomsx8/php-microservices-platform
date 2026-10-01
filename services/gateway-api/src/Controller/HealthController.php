<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HealthController
{
    #[Route('/health', name: 'health', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'service' => 'gateway-api',
            'status' => 'ok',
            'timestamp' => (new \DateTime())->format('c')
        ]);
    }
}