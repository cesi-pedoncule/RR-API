<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CategoryTest extends ApiTestCase
{

    private string $jwtToken;
    private array $categories;

    public function testGetCategories(int $nbCategories = 20): void
    {
        // Test GET /api/categories without authentication
        $response = static::createClient()->request('GET', '/api/categories', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbCategories, $response->toArray());
        
        // Test GET /api/categories with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/categories', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbCategories, $response->toArray());

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

    public function testCreateCategorie(): void
    {
        $newCategorie = [
            'name' => 'New Categorie of test',
            'isVisible' => true,
            'isDeleted' => false,
            'creator' => '/api/users/' . UserTest::getUserTestId()
        ];

        // Test POST /api/categories without authentication
        $response = static::createClient()->request('POST', '/api/categories', ['headers' => ['Accept' => 'application/json'], 'json' => $newCategorie]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test POST /api/categories with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('POST', '/api/categories', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $newCategorie]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['name' => $newCategorie['name'], 'isVisible' => $newCategorie['isVisible'], 'isDeleted' => $newCategorie['isDeleted']]);
    }

    public function testUpdateCategorie(): void
    {
        // Get the last categorie
        $this->testGetCategories(21);
        $lastCategorie = array_pop($this->categories);

        $updateCategorie = [
            'name' => 'Update Categorie of test',
            'isVisible' => false,
            'isDeleted' => true,
            'creator' => '/api/users/' . UserTest::getUserTestId()
        ];

        // Test PUT /api/categories/{id} without authentication
        $response = static::createClient()->request('PUT', '/api/categories/' . $lastCategorie['id'], ['headers' => ['Accept' => 'application/json'], 'json' => $updateCategorie]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test PUT /api/categories/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('PUT', '/api/categories/' . $lastCategorie['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $updateCategorie]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['name' => $updateCategorie['name'], 'isVisible' => $updateCategorie['isVisible'], 'isDeleted' => $updateCategorie['isDeleted']]);
    }

    public function testDeleteCategorie(): void
    {
        // Get the last categorie
        $this->testGetCategories(21);
        $lastCategorie = array_pop($this->categories);

        // Test DELETE /api/categories/{id} without authentication
        $response = static::createClient()->request('DELETE', '/api/categories/' . $lastCategorie['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /api/categories/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('DELETE', '/api/categories/' . $lastCategorie['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }
}
