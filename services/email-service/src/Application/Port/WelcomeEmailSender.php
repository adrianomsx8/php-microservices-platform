<?php

declare(strict_types=1);


namespace App\Application\Port;

interface WelcomeEmailSender
{
    public function sendWelcome(string $email, string $name): void;
}
