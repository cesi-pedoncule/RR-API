<?php

namespace App\Tests\Unit;

use App\Entity\Resource;
use App\Entity\User;
use App\Entity\UserLike;
use PHPUnit\Framework\TestCase;

class UserLikeTest extends TestCase
{
    private UserLike $userLike;

    protected function setUp():void
    {
        parent::setUp();

        $this->userLike = new UserLike();
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->userLike->setUser($value);

        $this->assertInstanceOf(UserLike::class, $response);
        $this->assertInstanceOf(User::class, $this->userLike->getUser());
        $this->assertEquals($value, $this->userLike->getUser());
        $this->assertEmpty($this->userLike->getResource());
        $this->assertEmpty($this->userLike->getLikeAt());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->userLike->setResource($value);

        $this->assertInstanceOf(UserLike::class, $response);
        $this->assertInstanceOf(Resource::class, $this->userLike->getResource());
        $this->assertEquals($value, $this->userLike->getResource());
        $this->assertEmpty($this->userLike->getUser());
        $this->assertEmpty($this->userLike->getLikeAt());
    }

    public function testGetLikeAt(): void
    {
        $value = new \DateTimeImmutable();

        $response = $this->userLike->setLikeAt($value);

        $this->assertInstanceOf(UserLike::class, $response);
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->userLike->getLikeAt());
        $this->assertEquals($value, $this->userLike->getLikeAt());
        $this->assertEmpty($this->userLike->getUser());
        $this->assertEmpty($this->userLike->getResource());
    }

}