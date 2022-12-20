<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class StateTest extends ApiTestCase
{
    private string $jwtToken;
    private array $states;

    /**
     * Return the first state id
     * 
     * @return string
     */
    public static function getStateTestId(): string
    {
        $response = static::createClient()->request('GET', '/states', ['headers' => ['Accept' => 'application/json']]);
        $states = $response->toArray();
        $first_state = array_shift($states);
        return $first_state['id'];
    }

    public function testGetStates(int $nbStates = 3): void
    {
        // Test GET /states without authentication
        $response = static::createClient()->request('GET', '/states', ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbStates, $response->toArray());

        // Test GET /states with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/states', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbStates, $response->toArray());

        $this->states = $response->toArray();
    }

    public function testGetState(): void
    {
        // Getting first state
        $this->testGetStates();
        $firstState = array_shift($this->states);

        $this->testGetStates();
        // Test GET /states/{id} without authentication
        $response = static::createClient()->request('GET', '/states/' . $firstState['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstState['id'], 'label' => $firstState['label']]);

        // Test GET /states/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/states/' . $firstState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstState['id'], 'label' => $firstState['label']]);
    }

    public function testCreateState(): void
    {
        $jsonValue = [
            'label' => 'Test state',
        ];

        // Test POST /states without authentication
        $response = static::createClient()->request('POST', '/states', ['json' => $jsonValue, 'headers' => ['Accept' => 'application/json']]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test POST /states with authentication (Admin account)
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('POST', '/states', ['json' => $jsonValue, 'headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['label' => $jsonValue['label']]);
    }

    public function testUpdateState(): void
    {
        $newJsonValue = [
            'label' => 'Test state updated',
        ];

        // Getting last state
        $this->testGetStates(4);
        $lastState = array_pop($this->states);

        // Test PUT /states/{id} without authentication
        $response = static::createClient()->request('PUT', '/states/' . $lastState['id'], ['json' => $newJsonValue, 'headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test PUT /states/{id} with authentication (Admin account)
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('PUT', '/states/' . $lastState['id'], ['json' => $newJsonValue, 'headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['label' => $newJsonValue['label']]);
    }

    public function testDeleteState(): void
    {
        // Getting last state
        $this->testGetStates(4);
        $lastState = array_pop($this->states);

        // Test DELETE /states/{id} without authentication
        $response = static::createClient()->request('DELETE', '/states/' . $lastState['id'], ['headers' => ['Accept' => 'application/json']]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /states/{id} with authentication (Admin account)
        $this->jwtToken = UserTest::userLoggedIn();

        $response = static::createClient()->request('DELETE', '/states/' . $lastState['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }
}