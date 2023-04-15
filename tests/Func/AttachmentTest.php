<?php

namespace App\Tests\Func;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AttachmentTest extends ApiTestCase
{
    private string $jwtToken;
    private array $attachments;

    public function testGetAttachments(int $nbAttachments = 1): void
    {
        // Test GET /attachments without authentication
        $response = static::createClient()->request('GET', '/attachments', ['headers' => ['Accept' => 'application/json']]);
        
        $this->attachments = $response->toArray();

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbAttachments, $response->toArray());

        // Test GET /attachments with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/attachments', ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertCount($nbAttachments, $response->toArray());
    }

    public function testGetAttachment(): void
    {
        $this->testGetAttachments();

        $firstAttachment = array_shift($this->attachments);

        // Test GET /attachments/{id} without authentication
        $response = static::createClient()->request('GET', '/attachments/' . $firstAttachment['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstAttachment['id'], 'fileUrl' => $firstAttachment['fileUrl'], 'fileName' => $firstAttachment['fileName'], 'type' => $firstAttachment['type'], 'createdAt' => $firstAttachment['createdAt'], 'user' => $firstAttachment['user'], 'resource' => $firstAttachment['resource']]);

        // Test GET /attachments/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('GET', '/attachments/' . $firstAttachment['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['id' => $firstAttachment['id'], 'fileUrl' => $firstAttachment['fileUrl'], 'fileName' => $firstAttachment['fileName'], 'type' => $firstAttachment['type'], 'createdAt' => $firstAttachment['createdAt'], 'user' => $firstAttachment['user'], 'resource' => $firstAttachment['resource']]);
    }

    public function testPostAttachment(): void
    {
        $firstResourceId = ResourceTest::getResourceTestId();
        
        $newAttachment = [
            'resource' => $firstResourceId,
            'file' => new UploadedFile('tests/TestsFiles/test.txt', 'test.txt', 'text/plain', null, true),
        ];

        // Test POST /attachments without authentication
        $response = static::createClient()->request('POST', '/attachments/resource', ['headers' => ['Accept' => 'application/json'], 'json' => $newAttachment]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test POST /attachments with authentication
        // $this->jwtToken = UserTest::userLoggedIn();
        // $response = static::createClient()->request('POST', '/attachments/resource', ['headers' => ['Accept' => 'application/json', 'Content-Type' => 'multipart/form-data'], 'auth_bearer' => $this->jwtToken, 'body' => $newAttachment]);

        // $this->assertResponseStatusCodeSame(201);
        // $this->assertResponseHeaderSame('content-type', 'multipart/form-data');
        // $this->assertJsonContains(['fileUrl' => 'test/TestsFiles/test.txt', 'fileName' => 'test.txt', 'resource' => "/resources/$firstResourceId"]);
        // TODO : Fix this test
    }

    public function testPutAttachment(): void
    {
        $this->testGetAttachments();

        $firstAttachment = array_shift($this->attachments);

        $newAttachment = [
            'fileName' => 'test-update.txt',
        ];

        // Test PUT /attachments/{id} without authentication
        $response = static::createClient()->request('PUT', '/attachments/' . $firstAttachment['id'], ['headers' => ['Accept' => 'application/json'], 'json' => $newAttachment]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test PUT /attachments/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('PUT', '/attachments/' . $firstAttachment['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken, 'json' => $newAttachment]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        $this->assertJsonContains(['fileName' => $newAttachment['fileName']]);
    }

    public function testDeleteAttachment(): void
    {
        $this->testGetAttachments();

        $firstAttachment = array_shift($this->attachments);

        // Test DELETE /attachments/{id} without authentication
        $response = static::createClient()->request('DELETE', '/attachments/' . $firstAttachment['id'], ['headers' => ['Accept' => 'application/json']]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertResponseHeaderSame('content-type', 'application/json');
        $this->assertJsonContains(['code' => 401, 'message' => 'JWT Token not found']);

        // Test DELETE /attachments/{id} with authentication
        $this->jwtToken = UserTest::userLoggedIn();
        $response = static::createClient()->request('DELETE', '/attachments/' . $firstAttachment['id'], ['headers' => ['Accept' => 'application/json'], 'auth_bearer' => $this->jwtToken]);

        $this->assertResponseStatusCodeSame(204);
    }

}