<?php

namespace App\Controller\Category;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostCategoryController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(Category $data): Category|JsonResponse
    {
        // Check if the current user is logged
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Check if the current user is an admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => 'Only admins can edit categories.'], 403);
        }

        // Set the creator of the category
        $data->setCreator($this->getUser());

        $this->em->persist($data);
        $this->em->flush();
        return $data;
    }
}
