<?php

declare(strict_types=1);

// Run: php tests/architecture.php (no framework or external services needed).
spl_autoload_register(function (string $class): void {
    foreach (['user-service', 'email-service', 'gateway-api'] as $service) {
        $path = __DIR__.'/../services/'.$service.'/src/'.str_replace('\\', '/', substr($class, 4)).'.php';
        if (str_starts_with($class, 'App\\') && is_file($path)) {
            require $path;
            return;
        }
    }
});

function check(bool $condition, string $message): void {
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$users = new class implements App\Domain\User\UserRepository {
    public array $saved = [];
    public function save(App\Domain\User\User $user): int {
        $this->saved[] = $user;
        return 42;
    }
};
$hasher = new class implements App\Application\Port\PasswordHasher {
    public function hash(string $password): string { return 'hashed:'.$password; }
};
$events = new class implements App\Application\Port\EventPublisher {
    public array $published = [];
    public function publish(string $event, array $payload): void {
        $this->published[] = [$event, $payload];
    }
};
$register = new App\Application\RegisterUser($users, $hasher, $events);
$register->execute('Ana', 'ana@example.com', 'secret');
check($users->saved[0]->passwordHash === 'hashed:secret', 'Password must be hashed.');
check($events->published === [['user.created', ['id' => 42, 'name' => 'Ana', 'email' => 'ana@example.com']]], 'Event contract changed.');
foreach ([['', 'ana@example.com', 'secret'], ['Ana', 'invalid', 'secret'], ['Ana', 'ana@example.com', '']] as $input) {
    try {
        $register->execute(...$input);
        throw new RuntimeException('Invalid registration was accepted.');
    } catch (InvalidArgumentException) {}
}
check(count($users->saved) === 1 && count($events->published) === 1, 'Invalid registration caused side effects.');

$failingUsers = new class implements App\Domain\User\UserRepository {
    public function save(App\Domain\User\User $user): int { throw new RuntimeException('Database unavailable'); }
};
try {
    (new App\Application\RegisterUser($failingUsers, $hasher, $events))->execute('Ana', 'ana@example.com', 'secret');
    throw new LogicException('Persistence error was swallowed.');
} catch (RuntimeException $exception) {
    check($exception->getMessage() === 'Database unavailable', 'Unexpected failure.');
}
check(count($events->published) === 1, 'Event published before persistence.');

$mail = new class implements App\Application\Port\WelcomeEmailSender {
    public array $sent = [];
    public function sendWelcome(string $email, string $name): void { $this->sent[] = [$email, $name]; }
};
(new App\Application\SendWelcomeEmail($mail))->execute('ana@example.com', 'Ana');
check($mail->sent === [['ana@example.com', 'Ana']], 'Welcome email recipient changed.');

$upstream = new class implements App\Application\Port\UserService {
    public array $request = [];
    public function request(string $method, string $path, string $body, ?string $authorization): App\Application\ProxyResponse {
        $this->request = func_get_args();
        return new App\Application\ProxyResponse('{"error":"unauthorized"}', 401);
    }
};
$response = (new App\Application\ForwardUserRequest($upstream))->execute('GET', '/api/profile', '', 'Bearer token');
check($upstream->request === ['GET', '/api/profile', '', 'Bearer token'], 'Proxy request changed.');
check($response->status === 401 && $response->body === '{"error":"unauthorized"}', 'Upstream response changed.');

foreach (['user-service', 'email-service', 'gateway-api'] as $service) {
    foreach (['Domain', 'Application'] as $layer) {
        $directory = __DIR__.'/../services/'.$service.'/src/'.$layer;
        if (!is_dir($directory)) { continue; }
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
            if ($file->getExtension() !== 'php') { continue; }
            check(!preg_match('/(?:Symfony|Doctrine|App\\\\Infrastructure|App\\\\Entity)\\\\/', file_get_contents($file->getPathname())), 'Inner layer depends on infrastructure: '.$file);
        }
    }
}
echo "Architecture and use case checks passed.\n";
