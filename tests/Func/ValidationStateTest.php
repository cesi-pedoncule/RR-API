<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ValidationStateTest extends ApiTestCase
{
    private string $jwtToken;
    private array $validationStates;

    public function testGetValidationStates(int $nbValidationStates = 3): void
    {
        // Test GET /api/validation_states without authentication
        $response = static::createClient()->request('GET', '/api/validation_states', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbValidationStates, $response->toArray());
        
        // Test GET /api/validation_states with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/validation_states', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbValidationStates, $response->toArray());

        $this->validationStates = $response->toArray();
    }

    public function testGetValidationState(): void
    {
        // Getting first validation state
        $this->testGetValidationStates();
        $firstValidationState = array_shift($this->validationStates);

        $this->testGetValidationStates();
        // Test GET /api/validation_states/{id} without authentication
        $response = static::createClient()->request('GET', '/api/validation_states/' . $firstValidationState['id'], ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstValidationState['id'], 'state' => $firstValidationState['state']]);

        // Test GET /api/validation_states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/api/validation_states/' . $firstValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstValidationState['id'], 'state' => $firstValidationState['state']]);
    }

    public function testCreateValidationState(): void
    {
        $jsonValidationState = [
            'state' => '/api/states/' . StateTest::getStateTestId(),
            'moderator' => '/api/users/' . UserTest::getUserTestId(),
        ];

        // Test POST /api/validation_states without authentication
        $response = static::createClient()->request('POST', '/api/validation_states', ['headers' => ['Accept' => 'application/json'], 'json' => $jsonValidationState ]);
        $this->assertResponseStatusCodeSame(401);

        // Test POST /api/validation_states with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('POST', '/api/validation_states', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $jsonValidationState ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['state' => $jsonValidationState['state']]);
    }

    public function testUpdateValidationState(): void
    {
        // Getting last validation state
        $this->testGetValidationStates(4);
        $lastValidationState = array_pop($this->validationStates);

        $jsonValidationState = [
            'state' => '/api/states/' . StateTest::getStateTestId(),
            'moderator' => '/api/users/' . UserTest::getUserTestId(),
        ];

        // Test PUT /api/validation_states/{id} without authentication
        $response = static::createClient()->request('PUT', '/api/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'json' => $jsonValidationState ]);

        $this->assertResponseStatusCodeSame(401);

        // Test PUT /api/validation_states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('PUT', '/api/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $jsonValidationState ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['state' => $jsonValidationState['state']]);
    }

    public function testDeleteValidationState(): void
    {
        // Getting last validation state
        $this->testGetValidationStates(4);
        $lastValidationState = array_pop($this->validationStates);

        // Test DELETE /api/validation_states/{id} without authentication
        $response = static::createClient()->request('DELETE', '/api/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseStatusCodeSame(401);

        // Test DELETE /api/validation_states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('DELETE', '/api/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }
}