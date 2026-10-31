<?php

namespace App\Infrastructure\Mail;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender implements \App\Application\Port\WelcomeEmailSender
{
    public function __construct(
        private MailerInterface $mailer
    ) {}

    public function sendWelcome(
        string $email,
        string $name
    ): void {

        $name = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $message = (new Email())
            ->from('noreply@platform.com')
            ->to($email)
            ->subject('Bem-vindo!')
            ->html("
                <h1>Olá {$name}</h1>
                <p>Sua conta foi criada com sucesso.</p>
            ");

        $this->mailer->send($message);
    }
}
