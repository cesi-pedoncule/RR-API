<?php

namespace App\Entity;

use App\Repository\ValidationStateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV6 as Uuid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ValidationStateRepository::class)]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['validationState:read']],
    denormalizationContext: ['groups' => ['validationState:write']],
    operations: [
        new Get(
            normalizationContext: ['groups' => ['validationState:read']],
            name: 'get_validation_state',
            uriTemplate: '/validation_states/{id}',
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['validationState:read']],
            name: 'get_validation_states',
            uriTemplate: '/validation_states',
        ),
        new Post(
            denormalizationContext: ['groups' => ['validationState:write']],
            normalizationContext: ['groups' => ['validationState:read']],
            name: 'post_validation_state',
            uriTemplate: '/validation_states',
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only authenticated users can create validationStates.',
        ),
        new Put(
            denormalizationContext: ['groups' => ['validationState:write']],
            normalizationContext: ['groups' => ['validationState:read']],
            name: 'put_validation_state',
            uriTemplate: '/validation_states/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getCreator() == user',
            securityMessage: 'Only admins can edit other users validationStates.',
        ),
        new Delete(
            name: 'delete_validation_state',
            uriTemplate: '/validation_states/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getCreator() == user',
            securityMessage: 'Only admins can delete other users validationStates.',
        )
    ]


)]
#[ORM\HasLifecycleCallbacks]
class ValidationState
{
    public const VALIDATION_STATES = [
        self::VALIDATION_STATE_PENDING,
        self::VALIDATION_STATE_VALIDATED,
        self::VALIDATION_STATE_REJECTED,
    ];

    public const VALIDATION_STATE_PENDING = 1;
    public const VALIDATION_STATE_VALIDATED = 2;
    public const VALIDATION_STATE_REJECTED = 3;

    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['resource:read', 'validationState:read'])]
    private ?Uuid $id = null;

    
    #[ORM\ManyToOne(inversedBy: 'validationStates')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['resource:read', 'resource:write', 'validationState:read', 'validationState:write'])]
    private ?State $state = null;

    #[ORM\Column]
    #[Groups(['resource:read', 'validationState:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'validationStates')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['resource:read', 'validationState:read'])]
    private ?User $moderator = null;

    #[ORM\ManyToOne(inversedBy: 'validationStates', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $resource = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->resources = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
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

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

}
