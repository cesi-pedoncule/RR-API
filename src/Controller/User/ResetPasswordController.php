<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

#[AsController]
class ResetPasswordController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }


    public function __invoke(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        // Check if old password is correct
        $oldPassword = $request->attributes->get('oldPassword');
        $newPassword = $request->attributes->get('newPassword');

        if (empty($oldPassword) || empty($newPassword)) {
            return $this->json(['message' => 'Old password or new password is empty'], 400);
        }
        
        if (!$user->verifyPassword($oldPassword)) {
            return $this->json(['message' => 'Old password is incorrect'], 401);
        }

        // Reset password
        $user->setPassword($user->hashPassword($newPassword));
        
        $this->em->persist($user);
        $this->em->flush();

        return $this->json(['message' => 'Password has been reset'], 204);
    }
}