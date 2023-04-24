<?php

namespace App\Controller\Resource;

use App\Entity\Resource;
use App\Service\ResourceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PutResourceController extends AbstractController
{
    public function __construct(private ResourceManager $resourceManager)
    {
    }

    public function __invoke(Resource $data): Resource|JsonResponse
    {
        // Check if the user is logged
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Check if the user is the creator of the resource
        if ($data->getUser() !== $this->getUser()) {
            return new JsonResponse(['error' => 'You can only edit your own resources.'], 403);
        }

        // Update the resource & change the validation state to pending
        $data->addValidationState($this->resourceManager->createDefaultValidationState($data));

        return $data;
    }
}