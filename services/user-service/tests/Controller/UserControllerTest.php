<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserControllerTest extends WebTestCase
{
    public function testMalformedJsonIsRejected(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/users/register', [], [], ['CONTENT_TYPE' => 'application/json'], '{');
        self::assertResponseStatusCodeSame(400);
        self::assertJson($client->getResponse()->getContent());
    }

    public function testMissingFieldsAreRejected(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/users/register', [], [], ['CONTENT_TYPE' => 'application/json'], '{}');
        self::assertResponseStatusCodeSame(400);
        self::assertArrayHasKey('error', json_decode($client->getResponse()->getContent(), true));
    }
}
