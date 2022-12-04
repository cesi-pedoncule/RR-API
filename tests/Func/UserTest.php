<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    private string $jwtToken;

    public static function userLoggedIn(): string 
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['token'];
    }

    public function testGetUsers(): void
    {
        // Test GET /api/users without auth
        $response = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test GET /api/users with auth
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(10, $response->toArray());
    }
}