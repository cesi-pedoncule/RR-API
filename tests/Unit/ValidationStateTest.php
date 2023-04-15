<?php

namespace App\Tests\Unit;

use App\Entity\Resource;
use App\Entity\State;
use App\Entity\User;
use App\Entity\ValidationState;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ValidationStateTest extends TestCase
{
    private ValidationState $validationState;

    public function setUp(): void
    {
        parent::setUp();

        $this->validationState = new ValidationState();
    }

    public function testGetState(): void
    {
        $value = new State();

        $response = $this->validationState->setState($value);

        $this->assertInstanceOf(ValidationState::class, $response);
        $this->assertEquals($value, $this->validationState->getState());
        $this->assertEmpty($this->validationState->getUpdatedAt());
        $this->assertEmpty($this->validationState->getModerator());
        $this->assertEmpty($this->validationState->getResource());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->validationState->setUpdatedAt($value);

        $this->assertInstanceOf(ValidationState::class, $response);
        $this->assertEquals($value, $this->validationState->getUpdatedAt());
        $this->assertEmpty($this->validationState->getState());
        $this->assertEmpty($this->validationState->getModerator());
        $this->assertEmpty($this->validationState->getResource());
    }

    public function testGetModerator(): void
    {
        $value = new User;

        $response = $this->validationState->setModerator($value);

        $this->assertInstanceOf(ValidationState::class, $response);
        $this->assertEquals($value, $this->validationState->getModerator());
    }

    public function testGetResource(): void
    {
        $value = new Resource();

        $response = $this->validationState->setResource($value);

        $this->assertInstanceOf(ValidationState::class, $response);
        $this->assertEquals($value, $this->validationState->getResource());
        $this->assertEmpty($this->validationState->getState());
        $this->assertEmpty($this->validationState->getUpdatedAt());
        $this->assertEmpty($this->validationState->getModerator());
    }

    public function testPrePersist(): void
    {
        $oldId = $this->validationState->getId();
        $oldState = $this->validationState->getState();
        $oldUpdatedAt = $this->validationState->getUpdatedAt();
        $oldModerator = $this->validationState->getModerator();
        $oldResource = $this->validationState->getResource();

        $this->validationState->setCreatedAtValue();

        $this->assertInstanceOf(DateTimeImmutable::class, $this->validationState->getUpdatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->validationState->getUpdatedAt());
        $this->assertEquals($oldId, $this->validationState->getId());
        $this->assertEquals($oldState, $this->validationState->getState());
        $this->assertEquals($oldModerator, $this->validationState->getModerator());
        $this->assertEquals($oldResource, $this->validationState->getResource());
    }

    public function testPreUpdate(): void
    {
        $oldId = $this->validationState->getId();
        $oldState = $this->validationState->getState();
        $oldUpdatedAt = $this->validationState->getUpdatedAt();
        $oldModerator = $this->validationState->getModerator();
        $oldResource = $this->validationState->getResource();

        $this->validationState->setUpdatedAtValue();

        $this->assertInstanceOf(DateTimeImmutable::class, $this->validationState->getUpdatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->validationState->getUpdatedAt());
        $this->assertEquals($oldId, $this->validationState->getId());
        $this->assertEquals($oldState, $this->validationState->getState());
        $this->assertEquals($oldModerator, $this->validationState->getModerator());
        $this->assertEquals($oldResource, $this->validationState->getResource());
    }

}