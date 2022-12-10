<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    private string $jwtToken;
    private array $users;

    public static function userLoggedIn(): string 
    {
        $response = static::createClient()->request('POST', '/api/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['token'];
    }

    public function testGetUsers(int $nbUsers = 10): void
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
        $this->assertCount($nbUsers, $response->toArray());

        // Save users for later use
        $this->users = $response->toArray();
    }

    public function testGetUser(): void
    {
        // Load users
        $this->testGetUsers();
        $first_user = array_shift($this->users);

        // Test GET /api/users/{id} without auth
        $response = static::createClient()->request('GET', '/api/users/'. $first_user['id'], ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Test GET /api/users/{id} with auth
        $response = static::createClient()->request('GET', '/api/users/' . $first_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $first_user['id'], 'email' => $first_user['email']]);
    }

    public function testCreateUser(): void
    {
        // Test POST /api/users without auth
        $response = static::createClient()->request('POST', '/api/users', ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'test-new-user@example.com',
            'name' => 'test',
            'firstname' => 'test',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }

    public function deleteUser(): void
    {
        // Load users
        $this->testGetUsers(11);
        $last_user = array_pop($this->users);

        // Test DELETE /api/users/{id} without auth
        $response = static::createClient()->request('DELETE', '/api/users/' . $last_user['id'], ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /api/users/{id} with auth
        $response = static::createClient()->request('DELETE', '/api/users/' . $last_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseStatusCodeSame(204);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }

    public function testUpdateUser(): void
    {
        // Load users
        $this->testGetUsers(11);
        $first_user = array_shift($this->users);

        // Test PUT /api/users/{id} without auth
        $response = static::createClient()->request('PUT', '/api/users/' . $first_user['id'], ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'new-email@example.com',
            'name' => 'new-name',
            'firstname' => 'new-firstname',
            'password' => 'new-password',
            'roles' => ['ROLE_USER'],
        ]]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test PUT /api/users/{id} with auth
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('PUT', '/api/users/' . $first_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => [
            'email' => 'new-email@example.com',
            'name' => 'new-name',
            'firstname' => 'new-firstname',
            'password' => 'new-password',
            'roles' => ['ROLE_USER'],
        ]]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['email' => 'new-email@example.com', 'name' => 'new-name', 'firstname' => 'new-firstname']);
    }
}