<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ResourceTest extends ApiTestCase
{   
    private string $jwtToken;
    private array $resources;

    public function testGetResources(int $nbResources = 20): void
    {
        // Test GET /api/resources without authentication
        $response = static::createClient()->request('GET', '/api/resources', ['headers' => ['Accept' => 'application/json']]);

        $this->resources = $response->toArray();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbResources, $response->toArray());

        // Test GET /api/resources with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/resources', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbResources, $response->toArray());
    }

    public function testGetResource(): void
    {
        $this->testGetResources();
        // Test GET /api/resources/{id} without authentication
        $response = static::createClient()->request('GET', '/api/resources/' . array_shift($this->resources[0]), ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(array_shift($this->resources));

        // Test GET /api/resources/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/resources/' . array_shift($this->resources[0]), ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(array_shift($this->resources));
    }

    public function testCreateResource(): void
    {
        // Value of the new resource
        $json_value = [
            'title' => 'test new resource',
            'description' => 'Description of test',
            'isPublic' => true,
            'isDeleted' => false
        ];

        // Test POST /api/resources without authentication
        $response = static::createClient()->request('POST', '/api/resources', ['headers' => ['Accept' => 'application/json'], 'json' => $json_value]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test POST /api/resources with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('POST', '/api/resources', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $json_value]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['title' => $json_value['title'], 'description' => $json_value['description'], 'isPublic' => $json_value['isPublic'], 'isDeleted' => $json_value['isDeleted']]);
    }

    public function testEditResource(): void
    {
        $this->testGetResources(21);
        $last_resource = array_pop($this->resources);

        // Value of the new resource
        $json_value = [
            'title' => 'test new resource title',
            'description' => 'Description of test',
            'isPublic' => true,
            'isDeleted' => false
        ];

        // TODO : Fix this test
        // Test PUT /api/resources/{id} without authentication
        // $response = static::createClient()->request('PUT', '/api/resources/' . $last_resource['id'], ['headers' => ['Accept' => 'application/json'], 'json' => $json_value]);
        // $this->assertResponseStatusCodeSame(401);
        // $this->assertResponseHeaderSame('content-type', 'application/json');
        // $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test PUT /api/resources/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('PUT', '/api/resources/' . $last_resource['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $json_value]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['title' => $json_value['title'], 'description' => $json_value['description'], 'isPublic' => $json_value['isPublic'], 'isDeleted' => $json_value['isDeleted']]);
    }

    public function testDeleteResource(): void
    {
        $this->testGetResources(21);
        $last_resource = array_pop($this->resources);

        // TODO: Fix this test
        // Test DELETE /api/resources/{id} without authentication
        $response = static::createClient()->request('DELETE', '/api/resources/' . $last_resource['id'], ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /api/resources/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('DELETE', '/api/resources/' . $last_resource['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        $this->assertResponseStatusCodeSame(204);
    }
}