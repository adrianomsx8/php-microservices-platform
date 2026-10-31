<?php

declare(strict_types=1);


namespace App\Application\Port;

use App\Application\ProxyResponse;

interface UserService
{
    public function request(string $method, string $path, string $body, ?string $authorization): ProxyResponse;
}
