<?php

namespace App\Tests\Unit;

use App\Entity\Attachment;
use App\Entity\Resource;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class AttachmentTest extends TestCase
{
    private Attachment $attachment;

    protected function setUp():void
    {
        parent::setUp();

        $this->attachment = new Attachment();
    }

    public function testGetFile(): void
    {
        $value = new File('tests/TestsFiles/test.txt');

        $response = $this->attachment->setFile($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getFile());
        $this->assertInstanceOf(File::class, $this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getUser());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetFilePath(): void
    {
        $value = 'tests/TestsFiles/test.txt';

        $response = $this->attachment->setFilePath($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getUser());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetFileUrl(): void
    {
        $value = 'http://localhost/tests/TestsFiles/test.txt';

        $response = $this->attachment->setFileUrl($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getUser());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetFileName(): void
    {
        $value = 'test.txt';

        $response = $this->attachment->setFileName($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getUser());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetType(): void
    {
        $value = 'text/plain';

        $response = $this->attachment->setType($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getType());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getUser());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetCreatedAt(): void
    {
        $value = new \DateTimeImmutable();

        $response = $this->attachment->setCreatedAt($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getUser());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->attachment->setUser($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getUser());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getResource());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->attachment->setResource($value);

        $this->assertInstanceOf(Attachment::class, $response);
        $this->assertEquals($value, $this->attachment->getResource());
        $this->assertEmpty($this->attachment->getFile());
        $this->assertEmpty($this->attachment->getFilePath());
        $this->assertEmpty($this->attachment->getFileUrl());
        $this->assertEmpty($this->attachment->getFileName());
        $this->assertEmpty($this->attachment->getType());
        $this->assertEmpty($this->attachment->getCreatedAt());
        $this->assertEmpty($this->attachment->getUser());
    }

    public function testPrePersist(): void
    {
        $oldId = $this->attachment->getId();
        $oldFile = $this->attachment->getFile();
        $oldFilePath = $this->attachment->getFilePath();
        $oldFileUrl = $this->attachment->getFileUrl();
        $oldFileName = $this->attachment->getFileName();
        $oldType = $this->attachment->getType();
        $oldCreatedAt = $this->attachment->getCreatedAt();
        $oldUser = $this->attachment->getUser();
        $oldResource = $this->attachment->getResource();

        $this->attachment->setCreatedAtValue();

        $this->assertInstanceOf(\DateTimeImmutable::class, $this->attachment->getCreatedAt());
        $this->assertNotEquals($oldCreatedAt, $this->attachment->getCreatedAt());
        $this->assertEquals($oldId, $this->attachment->getId());
        $this->assertEquals($oldFile, $this->attachment->getFile());
        $this->assertEquals($oldFilePath, $this->attachment->getFilePath());
        $this->assertEquals($oldFileUrl, $this->attachment->getFileUrl());
        $this->assertEquals($oldFileName, $this->attachment->getFileName());
        $this->assertEquals($oldType, $this->attachment->getType());
        $this->assertEquals($oldUser, $this->attachment->getUser());
        $this->assertEquals($oldResource, $this->attachment->getResource());
    }
}


