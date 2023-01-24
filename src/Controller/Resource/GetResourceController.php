<?php

namespace App\Controller\Resource;

use App\Entity\Resource;
use App\Entity\User;
use App\Service\ResourceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetResourceController extends AbstractController
{
    public function __construct(private ResourceManager $resourceManager)
    {
    }

    public function __invoke(string $id)
    {
        // Get the resource
        $resource = $this->resourceManager->findActiveResourceById($id);

        // Check if the resource exists
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        // Check if the resource is public
        if (!$resource->isIsPublic()) {
            // Check if the user is logged in and is the owner of the resource
            if (!$this->getUser()) {
                throw $this->createAccessDeniedException();
            } else {
                $user = $this->getUser();
                
                if ($user instanceof User){
                    if ($user->getId() !== $resource->getUser()->getId()) {
                        throw $this->createAccessDeniedException();
                    }
                } else {
                    throw $this->createAccessDeniedException();
                }
            }
        }

        return $resource;
    }
}