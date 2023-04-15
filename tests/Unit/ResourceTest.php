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
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetDescription(): void
    {
        $value = 'Test description';

        $response = $this->resource->setDescription($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getDescription());
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetAttachments(): void
    {
        $value = new Attachment();

        $response = $this->resource->addAttachment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertTrue($this->resource->getAttachments()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testRemoveAttachments(): void
    {
        $value = new Attachment();

        $this->resource->addAttachment($value);
        $response = $this->resource->removeAttachment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertFalse($this->resource->getAttachments()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetCreatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->resource->setCreatedAt($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->resource->setUpdatedAt($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->resource->setUser($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEquals($value, $this->resource->getUser());
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testIsIsPublic(): void
    {
        $value = true;

        $response = $this->resource->setIsPublic($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertNotEmpty($this->resource->isIsPublic());
        $this->assertEquals($value, $this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());

        $value = false;

        $response = $this->resource->setIsPublic($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEquals($value, $this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetComments(): void
    {
        $value = new Comment();

        $response = $this->resource->addComment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertNotEmpty($this->resource->getComments());
        $this->assertEquals(1, $this->resource->getComments()->count());
        $this->assertTrue($this->resource->getComments()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());

        $response = $this->resource->removeComment($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEmpty($this->resource->getComments());
        $this->assertFalse($this->resource->getComments()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetValidationStates(): void
    {
        $value = new ValidationState();

        $response = $this->resource->addValidationState($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertNotEmpty($this->resource->getValidationStates());
        $this->assertEquals(1, $this->resource->getValidationStates()->count());
        $this->assertTrue($this->resource->getValidationStates()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getUserLikes());

        $response = $this->resource->removeValidationState($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertFalse($this->resource->getValidationStates()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getCategories());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testGetCategories(): void
    {
        $value = new Category();

        $response = $this->resource->addCategory($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertNotEmpty($this->resource->getCategories());
        $this->assertEquals(1, $this->resource->getCategories()->count());
        $this->assertTrue($this->resource->getCategories()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());

        $response = $this->resource->removeCategory($value);

        $this->assertInstanceOf(Resource::class, $response);
        $this->assertEmpty($this->resource->getCategories());
        $this->assertFalse($this->resource->getCategories()->contains($value));
        $this->assertEmpty($this->resource->getTitle());
        $this->assertEmpty($this->resource->getDescription());
        $this->assertEmpty($this->resource->getAttachments());
        $this->assertEmpty($this->resource->getCreatedAt());
        $this->assertEmpty($this->resource->getUpdatedAt());
        $this->assertEmpty($this->resource->getUser());
        $this->assertEmpty($this->resource->isIsPublic());
        $this->assertEmpty($this->resource->getComments());
        $this->assertEmpty($this->resource->getValidationStates());
        $this->assertEmpty($this->resource->getUserLikes());
    }

    public function testPrePersist(): void
    {
        $oldId = $this->resource->getId();
        $oldTitle = $this->resource->getTitle();
        $oldDescription = $this->resource->getDescription();
        $oldAttachments = $this->resource->getAttachments();
        $oldCreatedAt = $this->resource->getCreatedAt();
        $oldUpdatedAt = $this->resource->getUpdatedAt();
        $oldUser = $this->resource->getUser();
        $oldIsPublic = $this->resource->isIsPublic();
        $oldCategories = $this->resource->getCategories();
        $oldComments = $this->resource->getComments();
        $oldValidationStates = $this->resource->getValidationStates();
        $oldUserLikes = $this->resource->getUserLikes();

        $this->resource->setCreationValues();

        $this->assertEquals($oldId, $this->resource->getId());
        $this->assertEquals($oldTitle, $this->resource->getTitle());
        $this->assertEquals($oldDescription, $this->resource->getDescription());
        $this->assertEquals($oldAttachments, $this->resource->getAttachments());
        $this->assertNotEquals($oldCreatedAt, $this->resource->getCreatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->resource->getUpdatedAt());
        $this->assertEquals($oldUser, $this->resource->getUser());
        $this->assertEquals($oldIsPublic, $this->resource->isIsPublic());
        $this->assertEquals($oldCategories, $this->resource->getCategories());
        $this->assertEquals($oldComments, $this->resource->getComments());
        $this->assertEquals($oldValidationStates, $this->resource->getValidationStates());
        $this->assertEquals($oldUserLikes, $this->resource->getUserLikes());
    }

    public function testPreUpdate(): void
    {
        $oldId = $this->resource->getId();
        $oldTitle = $this->resource->getTitle();
        $oldDescription = $this->resource->getDescription();
        $oldAttachments = $this->resource->getAttachments();
        $oldCreatedAt = $this->resource->getCreatedAt();
        $oldUpdatedAt = $this->resource->getUpdatedAt();
        $oldUser = $this->resource->getUser();
        $oldIsPublic = $this->resource->isIsPublic();
        $oldCategories = $this->resource->getCategories();
        $oldComments = $this->resource->getComments();
        $oldValidationStates = $this->resource->getValidationStates();
        $oldUserLikes = $this->resource->getUserLikes();

        $this->resource->setUpdatedAtValue();

        $this->assertEquals($oldId, $this->resource->getId());
        $this->assertEquals($oldTitle, $this->resource->getTitle());
        $this->assertEquals($oldDescription, $this->resource->getDescription());
        $this->assertEquals($oldAttachments, $this->resource->getAttachments());
        $this->assertEquals($oldCreatedAt, $this->resource->getCreatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->resource->getUpdatedAt());
        $this->assertEquals($oldUser, $this->resource->getUser());
        $this->assertEquals($oldIsPublic, $this->resource->isIsPublic());
        $this->assertEquals($oldCategories, $this->resource->getCategories());
        $this->assertEquals($oldComments, $this->resource->getComments());
        $this->assertEquals($oldValidationStates, $this->resource->getValidationStates());
        $this->assertEquals($oldUserLikes, $this->resource->getUserLikes());
    }

}