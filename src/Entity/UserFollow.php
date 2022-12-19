<?php

namespace App\Entity;

use App\Repository\UserFollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFollowRepository::class)]
class UserFollow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userFollows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userFollowers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $follower = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $followAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFollower(): ?user
    {
        return $this->follower;
    }

    public function setFollower(?user $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowAt(): ?\DateTimeImmutable
    {
        return $this->followAt;
    }

    public function setFollowAt(\DateTimeImmutable $followAt): self
    {
        $this->followAt = $followAt;

        return $this;
    }
}
