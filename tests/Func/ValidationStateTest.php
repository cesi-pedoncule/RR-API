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
        
        // // Test GET /api/validation_states with authentication
        // $this->jwtToken = UserTest::userLoggedIn();
        // $response = static::createClient()->request('GET', '/api/validation_states', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        // $this->assertResponseIsSuccessful();
        // $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        // $this->assertCount($nbValidationStates, $response->toArray());

        $this->validationStates = $response->toArray();
    }

    public function testGetValidationState(): void
    {
        $this->testGetValidationStates();
        // Test GET /api/validation_states/{id} without authentication
        $response = static::createClient()->request('GET', '/api/validation_states/' . array_shift($this->validationStates[0]), ['headers' => ['Accept' => 'application/json']]);
        
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(array_shift($this->validationStates));

        // // Test GET /api/validation_states/{id} with authentication
        // $this->jwtToken = UserTest::userLoggedIn();
        // $response = static::createClient()->request('GET', '/api/validation_states/' . array_shift($this->validationStates[0]), ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        // $this->assertResponseIsSuccessful();
        // $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        // $this->assertJsonContains(array_shift($this->validationStates));
    }
}