<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CategoryTest extends ApiTestCase
{

    private string $jwtToken;
    private array $categories;

    public function testGetCategories(): void
    {
        // Test GET /api/categories without authentication
        $response = static::createClient()->request('GET', '/api/categories', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(20, $response->toArray());
        
        // Test GET /api/categories with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/categories', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount(20, $response->toArray());

        $this->categories = $response->toArray();
    }

    public function testGetCategorie(): void
    {
        $this->testGetCategories();
        $firstCategory = array_shift($this->categories);
        
        // Test GET /api/categories/{id} without authentication
        $response = static::createClient()->request('GET', '/api/categories/' . $firstCategory['id'], ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstCategory['id'], 'name' => $firstCategory['name'], 'isVisible' => $firstCategory['isVisible'], 'isDeleted' => $firstCategory['isDeleted']]);
        
        // Test GET /api/categories/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/categories/' . $firstCategory['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstCategory['id'], 'name' => $firstCategory['name'], 'isVisible' => $firstCategory['isVisible'], 'isDeleted' => $firstCategory['isDeleted']]);
    }
}
