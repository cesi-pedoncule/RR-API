<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class UserFollowTest extends ApiTestCase
{
    private string $jwtToken;
    private array $userFollows;

    /**
     * Return the first userFollow id
     * 
     * @return string
     */
    public static function getUserFollowTestId(): string
    {
        $response = static::createClient()->request('GET', '/user_follows', ['headers' => ['Accept' => 'application/json']]);
        $userFollows = $response->toArray();
        $first_userFollow = array_shift($userFollows);
        return $first_userFollow['id'];
    }

    public function testGetUserFollows(int $nbFollows = 10): void
    {
        // Test GET /user_follows without authentication
        $response = static::createClient()->request('GET', '/user_follows', ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbFollows, $response->toArray());

        // Test GET /user_follows with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/user_follows', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbFollows, $response->toArray());

        $this->userFollows = $response->toArray();
    }

    public function testGetUserFollow(): void
    {
        // Getting first userFollow
        $this->testGetUserFollows();
        $firstUserFollow = array_shift($this->userFollows);

        // Test GET /user_follows/{id} without authentication
        $response = static::createClient()->request('GET', '/user_follows/' . $firstUserFollow['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstUserFollow['id'], 'user' => $firstUserFollow['user'], 'follower' => $firstUserFollow['follower']]);

        // Test GET /user_follows/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/user_follows/' . $firstUserFollow['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstUserFollow['id'], 'user' => $firstUserFollow['user'], 'follower' => $firstUserFollow['follower']]);
    }

    public function testDeleteUserFollow(): void
    {
        // Getting the last userFollow
        $this->testGetUserFollows();
        $lastUserFollow = array_pop($this->userFollows);

        // Test DELETE /user_follows/{id} without authentication
        $response = static::createClient()->request('DELETE', '/user_follows/' . $lastUserFollow['id'], ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /user_follows/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('DELETE', '/user_follows/' . $lastUserFollow['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }
}