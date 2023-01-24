<?php

namespace App\Controller\Category;

use App\Service\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetCollectionCategoryController extends AbstractController
{
    public function __construct(private CategoryManager $categoryManager)
    {
    }

    public function __invoke()
    {
        return $this->categoryManager->findVisibleActivesCategories();
    }
}