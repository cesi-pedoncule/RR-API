<?php

namespace App\Controller\ValidationState;

use App\Entity\ValidationState;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PutValidationStateController extends AbstractController
{
    public function __invoke(ValidationState $data): ValidationState
    {
        // If the user is null ask jwt to throw an exception
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Adding the user logged in to the new validationState
        $data->setModerator($this->getUser());

        return $data;
    }
}