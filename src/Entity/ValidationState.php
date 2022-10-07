<?php

namespace App\Entity;

use App\Repository\ValidationStateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValidationStateRepository::class)]
class ValidationState
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $state = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'validationStates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $moderator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getModerator(): ?User
    {
        return $this->moderator;
    }

    public function setModerator(?User $moderator): self
    {
        $this->moderator = $moderator;

        return $this;
    }
}
