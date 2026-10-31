<?php

declare(strict_types=1);


namespace App\Application;

use App\Application\Port\WelcomeEmailSender;

final class SendWelcomeEmail
{
    public function __construct(private WelcomeEmailSender $sender) {}

    public function execute(string $email, string $name): void
    {
        $this->sender->sendWelcome($email, $name);
    }
}
