<?php

namespace App\Tests\Unit;

use App\Entity\Attachment;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\User;
use App\Entity\ValidationState;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp():void
    {
        parent::setUp();

        $this->user = new User();
    }

    public function testGetEmail(): void
    {
        $value = 'example@example.com';
        
        $response = $this->user->setEmail($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getEmail());
        $this->assertEquals($value, $this->user->getUserIdentifier());
    }
    
    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];
        
        $response = $this->user->setRoles($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertContains('ROLE_USER', $this->user->getRoles());
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
    }

    public function testGetPassword(): void
    {
        $value = 'password';
        
        $response = $this->user->setPassword($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getPassword());
    }

    public function testGetName(): void
    {
        $value = 'name';
        
        $response = $this->user->setName($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getName());
    }

    public function testGetFirstname(): void
    {
        $value = 'firstname';
        
        $response = $this->user->setFirstname($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getFirstname());
    }

    public function testGetCreatedAt(): void
    {
        $value = new \DateTimeImmutable();
        
        $response = $this->user->setCreatedAt($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getCreatedAt());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new \DateTimeImmutable();
        
        $response = $this->user->setUpdatedAt($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getUpdatedAt());
    }

    public function testIsIsBanned(): void
    {
        $value = true;
        
        $response = $this->user->setIsBanned($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->isIsBanned());
    }

    public function testGetAttachments(): void
    {
        $value = new Attachment();
        
        $response = $this->user->addAttachment($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getAttachments());
        $this->assertCount(1, $this->user->getAttachments());

        $response = $this->user->removeAttachment($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getAttachments());
        $this->assertCount(0, $this->user->getAttachments());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->user->addResource($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getResources());
        $this->assertCount(1, $this->user->getResources());

        $response = $this->user->removeResource($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getResources());
        $this->assertCount(0, $this->user->getResources());
    }

    public function testGetValidationStates(): void
    {
        $value = new ValidationState();

        $response = $this->user->addValidationState($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getValidationStates());
        $this->assertCount(1, $this->user->getValidationStates());

        $response = $this->user->removeValidationState($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getValidationStates());
        $this->assertCount(0, $this->user->getValidationStates());
    }

    public function testGetCategories(): void
    {
        $value = new Category();

        $response = $this->user->addCategory($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getCategories());
        $this->assertCount(1, $this->user->getCategories());

        $response = $this->user->removeCategory($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getCategories());
        $this->assertCount(0, $this->user->getCategories());
    }

    public function testGetComments(): void
    {
        $value = new Comment();

        $response = $this->user->addComment($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getComments());
        $this->assertCount(1, $this->user->getComments());

        $response = $this->user->removeComment($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getComments());
        $this->assertCount(0, $this->user->getComments());
    }
}
