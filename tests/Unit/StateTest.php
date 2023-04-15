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

    public function testGetLabel(): void
    {
        $value = 'Test state label';

        $response = $this->state->setLabel($value);

        $this->assertInstanceOf(State::class, $response);
        $this->assertEquals($value, $this->state->getLabel());
        $this->assertEmpty($this->state->getValidationStates());
    }

    public function testGetValidationStates(): void
    {
        $value = new ValidationState();

        $response = $this->state->addValidationState($value);

        $this->assertInstanceOf(State::class, $response);
        $this->assertEquals(1, $this->state->getValidationStates()->count());
        $this->assertEquals($value, $this->state->getValidationStates()->first());
        $this->assertTrue($this->state->getValidationStates()->contains($value));  
        $this->assertEmpty($this->state->getLabel());
        
        $response = $this->state->removeValidationState($value);
        
        $this->assertInstanceOf(State::class, $response);
        $this->assertEmpty($this->state->getValidationStates());
        $this->assertFalse($this->state->getValidationStates()->contains($value));
        $this->assertEmpty($this->state->getLabel());
    }
}