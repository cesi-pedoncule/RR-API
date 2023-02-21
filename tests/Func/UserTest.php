<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserTest extends ApiTestCase
{
    private string $jwtToken;
    private array $users;

    /**
     * Return a JWT token for a user
     *
     * @return string
     */
    public static function userLoggedIn(): string 
    {
        $response = static::createClient()->request('POST', '/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['token'];
    }

    /**
     * Return a JWT refresh token for a user
     *
     * @return string
     */
    public static function userRefresh(): string
    {
        $response = static::createClient()->request('POST', '/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);
        return $response->toArray()['refresh_token'];
    }

    /**
     * Return the first user id
     * 
     * @return string
     */
    public static function getUserTestId(): string
    {
        $jwtToken = self::userLoggedIn();
        $response = static::createClient()->request('GET', '/users', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $jwtToken]);
        $users = $response->toArray();
        $first_user = array_shift($users);
        return $first_user['id'];
    }

    public function testGetUsers(int $nbUsers = 10): void
    {
        // Test GET /users without auth
        $response = static::createClient()->request('GET', '/users', ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbUsers, $response->toArray());

        // Test GET /users with auth
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('GET', '/users', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
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

        // Test GET /users/{id} without auth
        $response = static::createClient()->request('GET', '/users/'. $first_user['id'], ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        // Test GET /users/{id} with auth
        $response = static::createClient()->request('GET', '/users/' . $first_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $first_user['id'], 'email' => $first_user['email']]);
    }

    public function testGetMe(): void
    {
        // Test GET /users/me without auth
        $response = static::createClient()->request('GET', '/users/me', ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test GET /users/me with auth
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('GET', '/users/me', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['email' => 'user0@example.com']);
    }

    public function testUserLogin(): void
    {
        // Test POST /login_check without auth
        $response = static::createClient()->request('POST', '/login_check', ['json' => [
            'username' => 'user0@example.com',
            'password' => 'password',
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testUserRefresh(): void
    {
        // Test POST /token/refresh without auth
        $response = static::createClient()->request('POST', '/token/refresh', ['json' => [
            'refresh_token' => UserTest::userRefresh(),
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testCreateUser(): void
    {
        // Test POST /users without auth
        $response = static::createClient()->request('POST', '/users', ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'test-new-user@example.com',
            'name' => 'test',
            'firstname' => 'test',
            'password' => 'password',
            'roles' => ['ROLE_USER'],
        ]]);
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }

    public function testUpdateUser(): void
    {
        // Load users
        $this->testGetUsers(11);
        $last_user = array_pop($this->users);

        // Test PUT /users/{id} without auth
        $response = static::createClient()->request('PUT', '/users/' . $last_user['id'], ['headers' => ['Accept' => 'application/json'], 'json' => [
            'email' => 'new-email@example.com',
            'name' => 'new-name',
            'firstname' => 'new-firstname',
            'password' => 'new-password',
            'roles' => ['ROLE_USER'],
        ]]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test PUT /users/{id} with auth
        $this->jwtToken = self::userLoggedIn();

        $response = static::createClient()->request('PUT', '/users/' . $last_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => [
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

    public function testDeleteUser(): void
    {
        // Load users
        $this->testGetUsers(11);
        $last_user = array_pop($this->users);

        // Test DELETE /users/{id} without auth
        $response = static::createClient()->request('DELETE', '/users/' . $last_user['id'], ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /users/{id} with auth
        $response = static::createClient()->request('DELETE', '/users/' . $last_user['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseStatusCodeSame(204);
    }
}