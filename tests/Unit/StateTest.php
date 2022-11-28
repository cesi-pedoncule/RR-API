<?php

namespace App\Tests\Unit;

use App\Entity\State;
use App\Entity\ValidationState;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    private State $state;

    protected function setUp():void
    {
        parent::setUp();

        $this->state = new State();
    }

    public function testGetLable(): void
    {
        $value = 'Test state label';

        $response = $this->state->setLabel($value);

        $this->assertInstanceOf(State::class, $response);
        $this->assertEquals($value, $this->state->getLabel());
    }

    public function testGetValidationStates(): void
    {
        $value = new ValidationState();

        $response = $this->state->addValidationState($value);

        $this->assertInstanceOf(State::class, $response);
        $this->assertTrue($this->state->getValidationStates()->contains($value));  
        
        $response = $this->state->removeValidationState($value);

        $this->assertInstanceOf(State::class, $response);
        $this->assertFalse($this->state->getValidationStates()->contains($value));
    }
}