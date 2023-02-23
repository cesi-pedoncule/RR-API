<?php

namespace App\Service;

use App\Entity\Resource;
use App\Entity\User;
use App\Entity\UserFollow;
use App\Entity\UserLike;
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
        if ($user->isIsBanned()) {
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
     * Disable a user account
     * 
     * @param User $currentUser
     * @param User $userToDisable
     * @return User
     */
    public function disableUser(User $currentUser, User $userToDisable): User
    {
        // Check if the current user is the user to disable or if the current user is an admin, disable the user else throw exception
        if ($currentUser === $userToDisable || in_array('ROLE_ADMIN', $currentUser->getRoles())) {
            $userToDisable->setIsBanned(true);
            $this->entityManager->persist($userToDisable);
            $this->entityManager->flush();
        } else {
            throw new \Exception('You are not allowed to disable this user');
        }
        
        // Disable the user
        $userToDisable->setIsBanned(true);
        $this->entityManager->persist($userToDisable);
        $this->entityManager->flush();

        return $userToDisable;
    }

    /**
     * Return user Entity from the user email
     * 
     * @param string $email
     * @return null|User
     */
    public function findUserByEmail(string $email): null|User
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        return $user;
    }

    /**
     * Follow an user
     * 
     * @param User $currentUser
     * @param User $userToFollow
     * @return UserFollow
     */
    public function followUser(User $currentUser, User $userToFollow): UserFollow
    {
        // Check if the current user is different from the user to follow
        if ($currentUser === $userToFollow) {
            throw new \Exception('You can\'t follow yourself');
        }

        // Check if the current user is already following the user to follow
        if (in_array($currentUser, $userToFollow->getUserFollowers()->toArray())) {
            throw new \Exception('You are already following this user');
        }

        // Add the current user to the user to follow followers
        $follow = (new UserFollow())
            ->setUser($userToFollow)
            ->setFollower($currentUser);

        $this->entityManager->persist($follow);
        $this->entityManager->flush();

        return $follow;
    }

    /**
     * Unfollow an user
     * 
     * @param User $currentUser
     * @param User $userToUnfollow
     * @return void
     */
    public function unfollowUser(User $currentUser, User $userToUnfollow): void
    {
        // Check if the current user is different from the user to unfollow
        if ($currentUser === $userToUnfollow) {
            throw new \Exception('You can\'t unfollow yourself');
        }

        // Check if the current user is following the user to unfollow
        if (!in_array($currentUser, $userToUnfollow->getUserFollowers()->toArray())) {
            throw new \Exception('You are not following this user');
        }

        // Remove the current user from the user to unfollow followers
        $follow = $this->entityManager->getRepository(UserFollow::class)->findOneBy(['user' => $userToUnfollow, 'follower' => $currentUser]);

        $this->entityManager->remove($follow);
        $this->entityManager->flush();
    }

    /**
     * Check if the current user is liking the resource
     *
     * @param User $user
     * @param Resource $resource
     * @return UserLike|false
     */
    public function checkIfUserIsLikingResource(User $user, Resource $resource): UserLike | false
    {
        $userLike = $this->entityManager->getRepository(UserLike::class)->findOneBy(['user' => $user, 'resource' => $resource]);

        return $userLike ? $userLike : false;
    }

    /**
     * Like a resource
     * 
     * @param User $user
     * @param Resource $resource
     * @return UserLike
     */
    public function likeResource(User $user, Resource $resource): UserLike
    {
        // Check if the user is already liking the resource
        if ($this->checkIfUserIsLikingResource($user, $resource)) {
            throw new \Exception('You are already liking this resource');
        }

        // Add the user to the resource liked users
        $like = (new UserLike())
            ->setUser($user)
            ->setResource($resource);

        $this->entityManager->persist($like);
        $this->entityManager->flush();

        return $like;
    }

    /**
     * Unlike a resource
     * 
     * @param User $user
     * @param Resource $resource
     * @return void
     */
    public function unlikeResource(User $user, Resource $resource): void
    {
        // Check if the user is liking the resource
        $userLike = $this->checkIfUserIsLikingResource($user, $resource);
        if (!$userLike) {
            throw new \Exception('You are not liking this resource');
        }
        
        $this->entityManager->remove($userLike);
        $this->entityManager->flush();
    }
}