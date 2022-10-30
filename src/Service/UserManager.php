<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager {
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**
     * Check if the user email is available
     *
     * @param string $email
     * @return boolean
     */
    public function checkIfNewUserEmailIsAvailable(string $email): bool
    {
        // Check if the email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } 

        // Check if the email is already used
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        return $user === null;
    }

    /**
     * Check user informations and return true if the user is available to use the application
     * 
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function userCanLogin(string $email, string $password): bool
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        // Check if the user exists
        if ($user === null) {
            return false;
        }

        // Check if the password is correct
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            return false;
        }

        // Check if the user is banned
        if ($user->getIsBanned()) {
            return false;
        }

        return $user === null;
    }

    /**
     * Create a new user account
     * 
     * @param string $email
     * @param string $password
     * @param string $name
     * @param string $firstname
     * @param array $roles
     * @return false|User
     */
    public function createUser(string $email, string $password, string $name, string $firstname, array $roles = ['ROLE_USER'], bool $isBanned = false): false|User
    {
        // Check if the email is available before the user creation
        if (!$this->checkIfNewUserEmailIsAvailable($email)) {
            return false;
        }
        // Create the new user account
        $user = (new User())
            ->setEmail($email)
            ->setPassword($password)
            ->setName($name)
            ->setFirstname($firstname)
            ->setRoles($roles)
            ->setIsBanned($isBanned);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Ban the user account
     * 
     * @param User $user
     * @return void
     */
    public function banUser(User $user): void
    {
        $user->setIsBanned(true);
        $this->entityManager->flush();
    }

    /**
     * Unban the user account
     * 
     * @param User $user
     * @return void
     */
    public function unbanUser(User $user): void
    {
        $user->setIsBanned(false);
        $this->entityManager->flush();
    }
}