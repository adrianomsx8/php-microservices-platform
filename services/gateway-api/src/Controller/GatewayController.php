<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GatewayController extends AbstractController
{
    #[Route('/api/login', methods: ['POST'])]
    public function login(
        Request $request,
        HttpClientInterface $client
    ): Response {

        $response = $client->request(
            'POST',
            'http://user-service:8000/api/login',
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ],
                'body' => $request->getContent()
            ]
        );

        return new Response(
            $response->getContent(false),
            $response->getStatusCode(),
            [
                'Content-Type' => 'application/json'
            ]
        );
    }
}