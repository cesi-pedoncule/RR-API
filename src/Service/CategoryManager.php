<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager {
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Check if the category name is available
     *
     * @param string $name
     * @return boolean
     */
    public function checkIfNewCategoryNameIsAvailable(string $name): bool
    {
        // Check if the name is valid
        if (strlen($name) < 3) {
            return false;
        } 

        // Check if the name is already used
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $name]);

        return $category === null;
    }

    /**
     * Create a new category
     *
     * @param User $currentUser
     * @param string $name
     * @param boolean $isVisible
     * @return Category
     */
    public function createNewCategory(User $currentUser, string $name, bool $isVisible = true): Category
    {
        $category = (new Category())
            ->setName($name)
            ->setIsVisible($isVisible)
            ->setCreator($currentUser)
            ->setCreatedAt(new DateTimeImmutable());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $category;
    }

    /**
     * Return a category by id if it is active
     * 
     * @param string $id
     * @return Category|null
     */
    public function findActiveCategoryById(string $id): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $id]);
    }

    /**
     * Return all Active categories and visible
     * @return array<Category>
     */
    public function findVisibleActivesCategories(): array
    {
        return $this->entityManager->getRepository(Category::class)->findBy(['isVisible' => true]);
    }

    /**
     * Find a category by name property
     * 
     * @param string $name
     * @return Category|null
     */
    public function findCategoryByName(string $name): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(['name' => $name]);
    }
}