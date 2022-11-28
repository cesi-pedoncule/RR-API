<?php

namespace App\Tests\Unit;

use App\Entity\Attachment;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\User;
use App\Entity\ValidationState;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class ResourceTest extends TestCase
{
    private Resource $resource;

    protected function setUp():void
    {
        parent::setUp();

        $this->resource = new Resource();
    }

    public function testGetTitle(): void
    {
        $value = 'Test title';

        $response = $this->resource->setTitle($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getTitle());
    }

    public function testGetDescription(): void
    {
        $value = 'Test description';

        $response = $this->resource->setDescription($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getDescription());
    }

    public function testGetAttachments(): void
    {
        $value = new Attachment();

        $response = $this->resource->addAttachment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertTrue($this->resource->getAttachments()->contains($value));
    }

    public function testRemoveAttachments(): void
    {
        $value = new Attachment();

        $this->resource->addAttachment($value);
        $response = $this->resource->removeAttachment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertFalse($this->resource->getAttachments()->contains($value));
    }

    public function testGetCreatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->resource->setCreatedAt($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getCreatedAt());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->resource->setUpdatedAt($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getUpdatedAt());
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->resource->setUser($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getUser());
    }

    public function testIsIsPublic(): void
    {
        $value = true;

        $response = $this->resource->setIsPublic($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->isIsPublic());

        $value = false;

        $response = $this->resource->setIsPublic($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->isIsPublic());
    }

    public function testIsIsDeleted(): void
    {
        $value = true;

        $response = $this->resource->setIsDeleted($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->isIsDeleted());

        $value = false;

        $response = $this->resource->setIsDeleted($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->isIsDeleted());
    }

    public function testGetComments(): void
    {
        $value = new Comment();

        $response = $this->resource->addComment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertTrue($this->resource->getComments()->contains($value));

        $response = $this->resource->removeComment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertFalse($this->resource->getComments()->contains($value));
    }

    public function testGetValidationStates(): void
    {
        $value = new ValidationState();

        $response = $this->resource->addValidationState($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertTrue($this->resource->getValidationStates()->contains($value));

        $response = $this->resource->removeValidationState($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertFalse($this->resource->getValidationStates()->contains($value));
    }

    public function testGetCategories(): void
    {
        $value = new Category();

        $response = $this->resource->addCategory($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertTrue($this->resource->getCategories()->contains($value));

        $response = $this->resource->removeCategory($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertFalse($this->resource->getCategories()->contains($value));
    }

}