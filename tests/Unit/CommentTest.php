<?php

namespace App\Tests\Unit;

use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    private Comment $comment;

    protected function setUp():void
    {
        parent::setUp();

        $this->comment = new Comment();
    }

    public function testGetComment(): void
    {
        $value = 'Test comment';

        $response = $this->comment->setComment($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getComment());
        $this->assertEmpty($this->comment->getUser());
        $this->assertEmpty($this->comment->getResource());
        $this->assertEmpty($this->comment->getCreatedAt());
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->comment->setUser($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getUser());
        $this->assertEmpty($this->comment->getComment());
        $this->assertEmpty($this->comment->getResource());
        $this->assertEmpty($this->comment->getCreatedAt());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->comment->setResource($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getResource());
        $this->assertEmpty($this->comment->getComment());
        $this->assertEmpty($this->comment->getUser());
        $this->assertEmpty($this->comment->getCreatedAt());
    }

    public function testGetCreatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->comment->setCreatedAt($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getCreatedAt());
        $this->assertEmpty($this->comment->getComment());
        $this->assertEmpty($this->comment->getUser());
        $this->assertEmpty($this->comment->getResource());
    }

    public function testGetPrePersist(): void
    {
        $oldId = $this->comment->getId();
        $oldComment = $this->comment->getComment();
        $oldUser = $this->comment->getUser();
        $oldResource = $this->comment->getResource();
        $oldCreatedAt = $this->comment->getCreatedAt();

        $this->comment->setCreatedAtValue();

        $this->assertEquals($oldId, $this->comment->getId());
        $this->assertEquals($oldComment, $this->comment->getComment());
        $this->assertEquals($oldUser, $this->comment->getUser());
        $this->assertEquals($oldResource, $this->comment->getResource());
        $this->assertNotEmpty($this->comment->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $this->comment->getCreatedAt());
        $this->assertNotEquals($oldCreatedAt, $this->comment->getCreatedAt());
    }
}