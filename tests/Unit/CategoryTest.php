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

    public function testPrePersist(): void
    {
        $oldId = $this->category->getId();
        $oldName = $this->category->getName();
        $oldResources = $this->category->getResources();
        $oldIsVisible = $this->category->isIsVisible();
        $oldCreatedAt = $this->category->getCreatedAt();
        $oldUpdatedAt = $this->category->getUpdatedAt();
        $oldCreator = $this->category->getCreator();

        $this->category->setCreatedAtValue();

        $this->assertEquals($oldId, $this->category->getId());
        $this->assertEquals($oldName, $this->category->getName());
        $this->assertEquals($oldResources, $this->category->getResources());
        $this->assertEquals($oldIsVisible, $this->category->isIsVisible());
        $this->assertNotEmpty($this->category->getCreatedAt());
        $this->assertNotEquals($oldCreatedAt, $this->category->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $this->category->getCreatedAt());
        $this->assertNotEmpty($this->category->getUpdatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->category->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $this->category->getUpdatedAt());
        $this->assertEquals($oldCreator, $this->category->getCreator());
    }

    public function testPreUpdate(): void
    {
        $oldId = $this->category->getId();
        $oldName = $this->category->getName();
        $oldResources = $this->category->getResources();
        $oldIsVisible = $this->category->isIsVisible();
        $oldCreatedAt = $this->category->getCreatedAt();
        $oldUpdatedAt = $this->category->getUpdatedAt();
        $oldCreator = $this->category->getCreator();

        $this->category->setUpdatedAtValue();

        $this->assertEquals($oldId, $this->category->getId());
        $this->assertEquals($oldName, $this->category->getName());
        $this->assertEquals($oldResources, $this->category->getResources());
        $this->assertEquals($oldIsVisible, $this->category->isIsVisible());
        $this->assertEquals($oldCreatedAt, $this->category->getCreatedAt());
        $this->assertNotEmpty($this->category->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $this->category->getUpdatedAt());
        $this->assertNotEquals($oldUpdatedAt, $this->category->getUpdatedAt());
        $this->assertEquals($oldCreator, $this->category->getCreator());
    }
}