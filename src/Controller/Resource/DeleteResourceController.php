<?php

namespace App\Controller\Resource;

use App\Service\ResourceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteResourceController extends AbstractController
{
    public function __construct(private ResourceManager $resourceManager)
    {
    }

    public function __invoke(string $id)
    {
        // Get the resource
        $resource = $this->resourceManager->findResourceById($id);

        // Check if the resource exists
        if (!$resource) {
            return $this->createAccessDeniedException();
        }

        // Check if the user is logged in and is an Administrator or is the creator of the resource
        if (!$this->getUser()) {
            return $this->createAccessDeniedException();
        } else {
            if (!in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                if ($resource->getUser() !== $this->getUser()) {
                    return $this->createAccessDeniedException();
                }
            }
        }
        $this->resourceManager->deleteResource($resource);
    }
}