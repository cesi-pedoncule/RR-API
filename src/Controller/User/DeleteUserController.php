<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class DeleteUserController extends AbstractController
{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Request $request): ?User
    {
        // Getting the user to delete
        $user = $this->userRepository->find($request->attributes->get('id'));

        // If the current user is not the user to delete or if the user to delete is an admin, throw an exception
        if ($this->getUser() != $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('You cannot delete this user');
        }
        // Disable the user
        $user->setIsBanned(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }
}