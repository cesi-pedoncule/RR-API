<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Entity\UserFollow;
use PHPUnit\Framework\TestCase;

class UserFollowTest extends TestCase
{
    private UserFollow $userFollow;

    protected function setUp():void
    {
        parent::setUp();

        $this->userFollow = new UserFollow();
    }

    public function testGetUser(): void
    {
        $value = new User();

        $response = $this->userFollow->setUser($value);

        $this->assertInstanceOf(UserFollow::class, $response);
        $this->assertEquals($value, $this->userFollow->getUser());
        $this->assertEmpty($this->userFollow->getFollower());
        $this->assertEmpty($this->userFollow->getFollowAt());
    }

    public function testGetFollower(): void
    {
        $value = new User();

        $response = $this->userFollow->setFollower($value);

        $this->assertInstanceOf(UserFollow::class, $response);
        $this->assertEquals($value, $this->userFollow->getFollower());
        $this->assertEmpty($this->userFollow->getUser());
        $this->assertEmpty($this->userFollow->getFollowAt());
    }

    public function testGetFollowAt(): void
    {
        $value = new \DateTimeImmutable();

        $response = $this->userFollow->setFollowAt($value);

        $this->assertInstanceOf(UserFollow::class, $response);
        $this->assertInstanceOf(\DateTimeImmutable::class, $this->userFollow->getFollowAt());
        $this->assertEquals($value, $this->userFollow->getFollowAt());
        $this->assertEmpty($this->userFollow->getUser());
        $this->assertEmpty($this->userFollow->getFollower());
    }
}