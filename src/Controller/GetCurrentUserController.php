<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetCurrentUserController extends AbstractController
{

    public function __invoke(): User
    {
        return $this->getUser();
    }

    // public function __invoke(): ?User
    // {
    //     $token = $this->tokenStorage->getToken();
    //     dd($token);

    //     if (!$token) {
    //         return null;
    //     }

    //     $user = $token->getUser();

    //     if (!$user instanceof User) {
    //         return null;
    //     }
    //     return $user;
    // }
}