<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ResourceTest extends ApiTestCase
{   
    private string $jwtToken;
    private array $resources;

    public function testGetResources(): void
    {
        // Test GET /api/resources without authentication
        $response = static::createClient()->request('GET', '/api/resources', ['headers' => ['Accept' => 'application/json']]);

        $this->resources = $response->toArray();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(20, $response->toArray());

        // Test GET /api/resources with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/resources', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(20, $response->toArray());
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
}
