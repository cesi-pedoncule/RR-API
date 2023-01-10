<?php

namespace App\Controller\Resource;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
use App\Service\ResourceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostResourceController extends AbstractController
{
    public function __construct(
        private ResourceRepository $resourceRepository, 
        private ResourceManager $resourceManager,
        private EntityManagerInterface $entityManager
    ){}

    public function __invoke(Resource $data): ?Resource
    {
        // If the user is null ask jwt to throw an exception
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Adding the user logged in & the default validation state to the new resource
        $data->setUser($this->getUser());
        $data->addValidationState($this->resourceManager->createDefaultValidationState($data));

        // Persisting the new resource
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }
}