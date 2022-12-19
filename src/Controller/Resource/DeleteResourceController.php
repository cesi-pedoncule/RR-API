<?php

namespace App\Controller\Resource;

use App\Entity\Resource;
use App\Repository\ResourceRepository;
use App\Service\ResourceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteResourceController extends AbstractController
{
    public function __construct(private ResourceRepository $resourceRepository, private ResourceManager $resourceManager)
    {
    }

    public function __invoke(Resource $data): ?Resource
    {
        // If the user is null ask jwt to throw an exception
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Check if the user is the owner of the resource or an admin
        if ($this->getUser() !== $data->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You are not allowed to delete this resource.');
        }

        $data = $this->resourceManager->disableResource($this->getUser(), $data);
        return $data;
    }
}