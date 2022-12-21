<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class ValidationStateTest extends ApiTestCase
{
    private string $jwtToken;
    private array $validationStates;

    public function testGetValidationStates(int $nbValidationStates = 4): void
    {
        // Test GET /validation_states without authentication
        $response = static::createClient()->request('GET', '/validation_states', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbValidationStates, $response->toArray());
        
        // Test GET /validation_states with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/validation_states', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

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
        // Test GET /validation_states/{id} without authentication
        $response = static::createClient()->request('GET', '/validation_states/' . $firstValidationState['id'], ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstValidationState['id'], 'state' => $firstValidationState['state']]);

        // Test GET /validation_states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/validation_states/' . $firstValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstValidationState['id'], 'state' => $firstValidationState['state']]);
    }

    public function testCreateValidationState(): void
    {
        $jsonValidationState = [
            'state' => '/states/' . StateTest::getStateTestId(),
            'moderator' => '/users/' . UserTest::getUserTestId(),
        ];

        // Test POST /validation_states without authentication
        $response = static::createClient()->request('POST', '/validation_states', ['headers' => ['Accept' => 'application/json'], 'json' => $jsonValidationState ]);
        $this->assertResponseStatusCodeSame(401);

        // Test POST /validation_states with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('POST', '/validation_states', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $jsonValidationState ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['state' => $jsonValidationState['state']]);
    }

    public function testUpdateValidationState(): void
    {
        // Getting last validation state
        $this->testGetValidationStates(5);
        $lastValidationState = array_pop($this->validationStates);

        $jsonValidationState = [
            'state' => '/states/' . StateTest::getStateTestId(),
            'moderator' => '/users/' . UserTest::getUserTestId(),
        ];

        // Test PUT /validation_states/{id} without authentication
        $response = static::createClient()->request('PUT', '/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'json' => $jsonValidationState ]);

        $this->assertResponseStatusCodeSame(401);

        // Test PUT /validation_states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('PUT', '/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $jsonValidationState ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['state' => $jsonValidationState['state']]);
    }

    public function testDeleteValidationState(): void
    {
        // Getting last validation state
        $this->testGetValidationStates(5);
        $lastValidationState = array_pop($this->validationStates);

        // Test DELETE /validation_states/{id} without authentication
        $response = static::createClient()->request('DELETE', '/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseStatusCodeSame(401);

        // Test DELETE /validation_states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('DELETE', '/validation_states/' . $lastValidationState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }
}