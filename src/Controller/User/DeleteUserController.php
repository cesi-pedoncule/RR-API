<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class DeleteUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager, private UserManager $userManager)
    {
    }

    public function __invoke(Request $request): ?User
    {
        // Getting the user to delete
        $user = $this->userRepository->find($request->attributes->get('id'));
        $user = $this->userManager->disableUser($this->getUser(), $user);
        return $user;
    }
}