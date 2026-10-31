<?php

declare(strict_types=1);


namespace App\Infrastructure\Http;

use App\Application\Port\UserService;
use App\Application\ProxyResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class HttpUserService implements UserService
{
    public function __construct(private HttpClientInterface $client) {}

    public function request(string $method, string $path, string $body, ?string $authorization): ProxyResponse
    {
        $headers = ['Content-Type' => 'application/json'];
        if ($authorization !== null) {
            $headers['Authorization'] = $authorization;
        }
        $response = $this->client->request($method, 'http://user-service:8000'.$path, [
            'headers' => $headers,
            'body' => $body,
        ]);

        return new ProxyResponse($response->getContent(false), $response->getStatusCode());
    }
}
