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
        $this->assertEmpty($this->category->getResources());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->getUpdatedAt());
        $this->assertEmpty($this->category->isIsVisible());
    }
    
    public function testGetResources(): void
    {
        $value = new Resource();

        $response = $this->category->addResource($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertTrue($this->category->getResources()->contains($value));
        $this->assertEquals($value, $this->category->getResources()->first());
        $this->assertEquals(1, $this->category->getResources()->count());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->getUpdatedAt());
        $this->assertEmpty($this->category->isIsVisible());
    }

    public function testRemoveResources(): void
    {
        $value = new Resource();

        $this->category->addResource($value);
        
        $this->assertEquals(1, $this->category->getResources()->count());
        $this->assertTrue($this->category->getResources()->contains($value));

        $response = $this->category->removeResource($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertFalse($this->category->getResources()->contains($value));
        $this->assertEquals(0, $this->category->getResources()->count());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->getUpdatedAt());
        $this->assertEmpty($this->category->isIsVisible());
    }

    public function testIsIsVisible(): void
    {
        $value = true;

        $response = $this->category->setIsVisible($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertNotEmpty($this->category->isIsVisible());
        $this->assertEquals($value, $this->category->isIsVisible());
        $this->assertEmpty($this->category->getResources());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->getUpdatedAt());

        $value = false;

        $response = $this->category->setIsVisible($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEmpty($this->category->isIsVisible());
        $this->assertEquals($value, $this->category->isIsVisible());
        $this->assertEmpty($this->category->getResources());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->getUpdatedAt());
    }

    public function testGetCreatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->category->setCreatedAt($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getCreatedAt());
        $this->assertEmpty($this->category->getResources());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getUpdatedAt());
        $this->assertEmpty($this->category->isIsVisible());
    }

    public function testGetUpdatedAt(): void
    {
        $value = new DateTimeImmutable();

        $response = $this->category->setUpdatedAt($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getUpdatedAt());
        $this->assertEmpty($this->category->getResources());
        $this->assertEmpty($this->category->getCreator());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->isIsVisible());
    }

    public function testGetCreator(): void
    {
        $value = new User();

        $response = $this->category->setCreator($value);

        $this->assertInstanceOf(Category::class, $response);
        $this->assertEquals($value, $this->category->getCreator());
        $this->assertEmpty($this->category->getResources());
        $this->assertEmpty($this->category->getCreatedAt());
        $this->assertEmpty($this->category->getUpdatedAt());
        $this->assertEmpty($this->category->isIsVisible());
    }
}