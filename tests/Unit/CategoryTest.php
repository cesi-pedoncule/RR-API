<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use App\Entity\Resource;
use App\Entity\User;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private Category $category;

    protected function setUp():void
    {
        parent::setUp();

        $this->category = new Category();
    }

    public function testGetName(): void
    {
        $value = 'Test category name';
        
        $response = $this->category->setName($value);
        
        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getName());
    }
    
    public function testGetResources(): void
    {
        $value = new Resource();

        $response = $this->category->addResource($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertTrue($this->category->getResources()->contains($value));        
    }

    public function testRemoveResources(): void
    {
        $value = new Resource();

        $this->category->addResource($value);
        $response = $this->category->removeResource($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertFalse($this->category->getResources()->contains($value));        
    }

    public function testIsIsVisible(): void
    {
        $value = true;

        $response = $this->category->setIsVisible($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->isIsVisible());

        $value = false;

        $response = $this->category->setIsVisible($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->isIsVisible());
    }

    public function testGetCreatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->category->setCreatedAt($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getCreatedAt());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->category->setUpdatedAt($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getUpdatedAt());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->category->setCreator($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getCreator());
    }

    public function testIsIsDeleted(): void
    {
        $value = true;

        $response = $this->category->setIsDeleted($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->isIsDeleted());

        $value = false;

        $response = $this->category->setIsDeleted($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->isIsDeleted());
    }
}