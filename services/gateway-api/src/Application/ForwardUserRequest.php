<?php

declare(strict_types=1);


namespace App\Application;

use App\Application\Port\UserService;

final class ForwardUserRequest
{
    public function __construct(private UserService $users) {}

    public function execute(string $method, string $path, string $body = '', ?string $authorization = null): ProxyResponse
    {
        return $this->users->request($method, $path, $body, $authorization);
    }
}
