<?php

namespace App\Tests\Unit;

use App\Entity\Attachment;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\User;
use App\Entity\UserFollow;
use App\Entity\UserLike;
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
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }
    
    public function testGetRoles(): void
    {
        $value = ['ROLE_ADMIN'];
        
        $response = $this->user->setRoles($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertContains('ROLE_USER', $this->user->getRoles());
        $this->assertContains('ROLE_ADMIN', $this->user->getRoles());
        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
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
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetFirstname(): void
    {
        $value = 'firstname';
        
        $response = $this->user->setFirstname($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getFirstname());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetCreatedAt(): void
    {
        $value = new \DateTimeImmutable();
        
        $response = $this->user->setCreatedAt($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getCreatedAt());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new \DateTimeImmutable();
        
        $response = $this->user->setUpdatedAt($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->getUpdatedAt());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testIsIsBanned(): void
    {
        $value = true;
        
        $response = $this->user->setIsBanned($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertEquals($value, $this->user->isIsBanned());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetAttachments(): void
    {
        $value = new Attachment();
        
        $response = $this->user->addAttachment($value);
        
        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getAttachments());
        $this->assertCount(1, $this->user->getAttachments());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeAttachment($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getAttachments());
        $this->assertCount(0, $this->user->getAttachments());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->user->addResource($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getResources());
        $this->assertCount(1, $this->user->getResources());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeResource($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getResources());
        $this->assertCount(0, $this->user->getResources());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetValidationStates(): void
    {
        $value = new ValidationState();

        $response = $this->user->addValidationState($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getValidationStates());
        $this->assertCount(1, $this->user->getValidationStates());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeValidationState($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getValidationStates());
        $this->assertCount(0, $this->user->getValidationStates());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetCategories(): void
    {
        $value = new Category();

        $response = $this->user->addCategory($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getCategories());
        $this->assertCount(1, $this->user->getCategories());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeCategory($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getCategories());
        $this->assertCount(0, $this->user->getCategories());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetComments(): void
    {
        $value = new Comment();

        $response = $this->user->addComment($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getComments());
        $this->assertCount(1, $this->user->getComments());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeComment($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getComments());
        $this->assertCount(0, $this->user->getComments());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetResourceLikes(): void
    {
        $value = new UserLike();

        $response = $this->user->addResourceLike($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getResourceLikes());
        $this->assertCount(1, $this->user->getResourceLikes());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeResourceLike($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getResourceLikes());
        $this->assertCount(0, $this->user->getResourceLikes());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getUserFollows());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testGetUserFollows(): void
    {
        $value = new UserFollow();

        $response = $this->user->addUserFollow($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertContains($value, $this->user->getUserFollows());
        $this->assertCount(1, $this->user->getUserFollows());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollowers());

        $response = $this->user->removeUserFollow($value);

        $this->assertInstanceOf(User::class, $response);
        $this->assertNotContains($value, $this->user->getUserFollows());
        $this->assertCount(0, $this->user->getUserFollows());
        $this->assertEmpty($this->user->getEmail());
        $this->assertEquals(['ROLE_USER'], $this->user->getRoles());
        $this->assertEmpty($this->user->getName());
        $this->assertEmpty($this->user->getFirstname());
        $this->assertEmpty($this->user->getCreatedAt());
        $this->assertEmpty($this->user->getUpdatedAt());
        $this->assertEmpty($this->user->isIsBanned());
        $this->assertEmpty($this->user->getAttachments());
        $this->assertEmpty($this->user->getResources());
        $this->assertEmpty($this->user->getValidationStates());
        $this->assertEmpty($this->user->getCategories());
        $this->assertEmpty($this->user->getComments());
        $this->assertEmpty($this->user->getResourceLikes());
        $this->assertEmpty($this->user->getUserFollowers());
    }

    public function testPrePersist(): void
    {
        $this->user->setPassword('password');

        $oldId = $this->user->getId();
        $oldEmail = $this->user->getEmail();
        $oldRoles = $this->user->getRoles();
        $oldPassword = $this->user->getPassword();
        $oldName = $this->user->getName();
        $oldFirstname = $this->user->getFirstname();
        $oldCreatedAt = $this->user->getCreatedAt();
        $oldUpdatedAt = $this->user->getUpdatedAt();
        $oldIsBanned = $this->user->isIsBanned();
        $oldAttachments = $this->user->getAttachments();
        $oldResources = $this->user->getResources();
        $oldValidationStates = $this->user->getValidationStates();
        $oldCategories = $this->user->getCategories();
        $oldComments = $this->user->getComments();
        $oldResourceLikes = $this->user->getResourceLikes();
        $oldUserFollows = $this->user->getUserFollows();
        $oldUserFollowers = $this->user->getUserFollowers();


        $this->user->setCreatedAtValue();

        $this->assertEquals($oldId, $this->user->getId());
        $this->assertEquals($oldEmail, $this->user->getEmail());
        $this->assertEquals($oldRoles, $this->user->getRoles());
        $this->assertNotEquals($oldPassword, $this->user->getPassword());
        $this->assertEquals($oldName, $this->user->getName());
        $this->assertEquals($oldFirstname, $this->user->getFirstname());
        $this->assertNotEquals($oldCreatedAt, $this->user->getCreatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->user->getUpdatedAt());
        $this->assertEquals($oldIsBanned, $this->user->isIsBanned());
        $this->assertEquals($oldAttachments, $this->user->getAttachments());
        $this->assertEquals($oldResources, $this->user->getResources());
        $this->assertEquals($oldValidationStates, $this->user->getValidationStates());
        $this->assertEquals($oldCategories, $this->user->getCategories());
        $this->assertEquals($oldComments, $this->user->getComments());
        $this->assertEquals($oldResourceLikes, $this->user->getResourceLikes());
        $this->assertEquals($oldUserFollows, $this->user->getUserFollows());
        $this->assertEquals($oldUserFollowers, $this->user->getUserFollowers());
    }

    public function testPreUpdate(): void
    {
        $this->user->setPassword('password');

        $oldId = $this->user->getId();
        $oldEmail = $this->user->getEmail();
        $oldRoles = $this->user->getRoles();
        $oldPassword = $this->user->getPassword();
        $oldName = $this->user->getName();
        $oldFirstname = $this->user->getFirstname();
        $oldCreatedAt = $this->user->getCreatedAt();
        $oldUpdatedAt = $this->user->getUpdatedAt();
        $oldIsBanned = $this->user->isIsBanned();
        $oldAttachments = $this->user->getAttachments();
        $oldResources = $this->user->getResources();
        $oldValidationStates = $this->user->getValidationStates();
        $oldCategories = $this->user->getCategories();
        $oldComments = $this->user->getComments();
        $oldResourceLikes = $this->user->getResourceLikes();
        $oldUserFollows = $this->user->getUserFollows();
        $oldUserFollowers = $this->user->getUserFollowers();

        $this->user->setUpdatedAtValue();

        $this->assertEquals($oldId, $this->user->getId());
        $this->assertEquals($oldEmail, $this->user->getEmail());
        $this->assertEquals($oldRoles, $this->user->getRoles());
        $this->assertEquals($oldPassword, $this->user->getPassword());
        $this->assertEquals($oldName, $this->user->getName());
        $this->assertEquals($oldFirstname, $this->user->getFirstname());
        $this->assertEquals($oldCreatedAt, $this->user->getCreatedAt());
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->user->getUpdatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->user->getUpdatedAt());
        $this->assertEquals($oldIsBanned, $this->user->isIsBanned());
        $this->assertEquals($oldAttachments, $this->user->getAttachments());
        $this->assertEquals($oldResources, $this->user->getResources());
        $this->assertEquals($oldValidationStates, $this->user->getValidationStates());
        $this->assertEquals($oldCategories, $this->user->getCategories());
        $this->assertEquals($oldComments, $this->user->getComments());
        $this->assertEquals($oldResourceLikes, $this->user->getResourceLikes());
        $this->assertEquals($oldUserFollows, $this->user->getUserFollows());
        $this->assertEquals($oldUserFollowers, $this->user->getUserFollowers());
    }
}
