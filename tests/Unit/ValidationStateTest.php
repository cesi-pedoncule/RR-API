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
    }

    public function testGetUpdatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->validationState->setUpdatedAt($value);

        $this->assertInstanceOf(ValidationState::class, $response);
        $this->assertEquals($value, $this->validationState->getUpdatedAt());
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
    }

}