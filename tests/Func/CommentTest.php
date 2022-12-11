<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CommentTest extends ApiTestCase
{
    private string $jwtToken;
    private array $comments;

    private string $userId;
    private string $resourceID;

    private function getUserId(): string
    {
        if (empty($this->userId)) {
            $this->jwtToken = UserTest::userLoggedIn();
            $response = static::createClient()->request('GET', '/api/users', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
            $this->userId = $response->toArray()[0]['id'];
        }
        return $this->userId;
    }

    private function getResourceId(): string
    {
        if (empty($this->resourceID)) {
            $this->jwtToken = UserTest::userLoggedIn();
            $response = static::createClient()->request('GET', '/api/resources', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
            $this->resourceID = $response->toArray()[0]['id'];
        }
        return $this->resourceID;
    }

    public function testGetComments(int $nbComments = 20): void
    {
        // Test GET /api/comments without authentication
        $response = static::createClient()->request('GET', '/api/comments', ['headers' => ['Accept' => 'application/json']]);

        $this->comments = $response->toArray();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbComments, $response->toArray());

        // Test GET /api/comments with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        
        $response = static::createClient()->request('GET', '/api/comments', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbComments, $response->toArray());

        $this->comments = $response->toArray();
    }

    public function testGetComment(): void
    {
        $this->testGetComments();
        $first_comment = array_shift($this->comments);

        // Test GET /api/comments/{id} without authentication
        $response = static::createClient()->request('GET', '/api/comments/' . $first_comment['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        // $this->assertJsonContains(array_shift($this->comments));

        // Test GET /api/comments/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/comments/' . $first_comment['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $first_comment['id'], 'comment' => $first_comment['comment'], 'isDeleted' => $first_comment['isDeleted'], 'resource' => $first_comment['resource'], 'user' => $first_comment['user']]);
    }

    public function testCreateComment(): void
    {
        $this->jwtToken = UserTest::userLoggedIn();

        $json_value = [
            'comment' => 'Test comment',
            'resource' => '/api/resources/' . $this->getResourceId(),
            'user' => '/api/users/' . $this->getUserId(),
            'isDeleted' => false
        ];

        // Test POST /api/comments without authentication
        $response = static::createClient()->request('POST', '/api/comments', ['headers' => ['Accept' => 'application/json'], 'json' => $json_value]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // Test POST /api/comments with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('POST', '/api/comments', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $json_value]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['comment' => 'Test comment', 'isDeleted' => false]);
    }

    public function testUpdateComment(): void
    {
        $this->testGetComments(21);
        $first_comment = array_shift($this->comments);

        $json_value = [
            'comment' => 'Test new comment',
            'resource' => '/api/resources/' . $this->getResourceId(),
            'user' => '/api/users/' . $this->getUserId(),
            'isDeleted' => false
        ];

        // TODO : Fix this test
        // Test PUT /api/comments/{id} without authentication
        // $response = static::createClient()->request('PUT', '/api/comments/' . $first_comment['id'], ['headers' => ['Accept' => 'application/json'], 'json' => $json_value]);

        // $this->assertResponseStatusCodeSame(401);
        // $this->assertResponseHeaderSame('content-type', 'application/json');
        // $this->assertJsonContains(['message' => 'JWT Token not found']);

        // Test PUT /api/comments/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('PUT', '/api/comments/' . $first_comment['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $json_value]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['comment' => 'Test new comment', 'isDeleted' => false]);
    }

    public function testDeleteComment(): void
    {
        $this->testGetComments(21);
        $last_comment = array_pop($this->comments);

        // TODO : Fix this test
        // Test DELETE /api/comments/{id} without authentication
        // $response = static::createClient()->request('DELETE', '/api/comments/' . $last_comment['id'], ['headers' => ['Accept' => 'application/json']]);

        // $this->assertResponseStatusCodeSame(401);
        // $this->assertResponseHeaderSame('content-type', 'application/json');
        // $this->assertJsonContains(['message' => 'JWT Token not found']);

        // Test DELETE /api/comments/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('DELETE', '/api/comments/' . $last_comment['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
    }

}