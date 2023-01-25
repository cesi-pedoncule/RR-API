<?php

namespace App\Controller\Category;

use App\Service\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetCategoryController extends AbstractController
{
    public function __construct(private CategoryManager $categoryManager)
    {
    }

    public function __invoke(string $id)
    {
        $category = $this->categoryManager->findActiveCategoryById($id);

        // Check if the category exists
        if (!$category) {
            return $this->createAccessDeniedException();
        }

        // Check if the category is visible (Else only Admins can see it)
        if (!$category->isIsVisible()) {
            // Check if the user is logged in and is an Administrator
            if (!$this->getUser()) {
                return $this->createAccessDeniedException();
            } else {
                if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    return $this->createAccessDeniedException();
                } 
            }
            return $this->createAccessDeniedException();
        }

        return $category;
    }
}