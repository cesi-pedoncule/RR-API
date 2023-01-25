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
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->comment->setUser($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getUser());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->comment->setResource($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getResource());
    }

    public function testGetCreatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->comment->setCreatedAt($value);

        $this->assertInstanceOf(Comment::class, $response);
        $this->assertEquals($value, $this->comment->getCreatedAt());
    }
}