<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserLikeTest extends ApiTestCase
{
    private string $jwtToken;
    private array $userLikes;

    public function testGetUserLikes(int $nbUserLikes = 10): void
    {
        // Test GET /user_likes without authentication
        $response = static::createClient()->request('GET', '/user_likes', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbUserLikes, $response->toArray());

        // Test GET /user_likes with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/user_likes', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbUserLikes, $response->toArray());

        $this->userLikes = $response->toArray();
    }

    public function testGetUserLike(): void
    {
        // Getting first user_like
        $this->testGetUserLikes();
        $firstUserLike = array_shift($this->userLikes);

        // Test GET /user_likes/{id} without authentication
        $response = static::createClient()->request('GET', '/user_likes/' . $firstUserLike['id'], ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstUserLike['id'], 'user' => $firstUserLike['user'], 'resource' => $firstUserLike['resource']]);

        // Test GET /user_likes/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/user_likes/' . $firstUserLike['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstUserLike['id'], 'user' => $firstUserLike['user'], 'resource' => $firstUserLike['resource']]);
    }

    public function testCreateUserLike(): void
    {
        $jsonValue = [
            'user' => '/users/' . UserTest::getUserTestId(),
            'resource' => '/resources/' . ResourceTest::getResourceTestId()
        ];

        // Test POST /user_likes without authentication
        $response = static::createClient()->request('POST', '/user_likes', ['headers' => ['Accept' => 'application/json'], 'json' => $jsonValue]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test POST /user_likes with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('POST', '/user_likes', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $jsonValue]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
    }

    public function testDelete(): void
    {
        // Getting the last user_like
        $this->testGetUserLikes(11);
        $lastUserLike = array_pop($this->userLikes);

        // Test DELETE /user_likes/{id} without authentication
        $response = static::createClient()->request('DELETE', '/user_likes/' . $lastUserLike['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /user_likes/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('DELETE', '/user_likes/' . $lastUserLike['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }
}