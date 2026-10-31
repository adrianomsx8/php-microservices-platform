<?php

declare(strict_types=1);


namespace App\Application;

final class ProxyResponse
{
    public function __construct(public readonly string $body, public readonly int $status) {}
}
