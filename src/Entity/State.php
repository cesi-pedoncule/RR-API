<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\StateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StateRepository::class)]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['state:read']],
    denormalizationContext: ['groups' => ['state:write']],
    operations: [
        new Get(
            name: 'get_state',
            uriTemplate: '/states/{id}'
        ),
        new GetCollection(
            name: 'get_states',
            uriTemplate: '/states'
        ),
        new Post(
            name: 'post_state',
            uriTemplate: '/states',
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can create states.',
        ),
        new Put(
            name: 'put_state',
            uriTemplate: '/states/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can edit states.',
        ),
        new Delete(
            name: 'delete_state',
            uriTemplate: '/states/{id}',
            security: 'is_granted("ROLE_ADMIN")',
            securityMessage: 'Only admins can delete states.',
        )
    ]
)]
class State
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['state:read', 'validationState:read', 'resource:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['state:read', 'state:write', 'validationState:read', 'resource:read'])]
    private ?string $label = null;

    #[ORM\OneToMany(mappedBy: 'state', targetEntity: ValidationState::class)]
    #[Groups(['state:read'])]
    private Collection $validationStates;

    public function __construct()
    {
        $this->validationStates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, ValidationState>
     */
    public function getValidationStates(): Collection
    {
        return $this->validationStates;
    }

    public function addValidationState(ValidationState $validationState): self
    {
        if (!$this->validationStates->contains($validationState)) {
            $this->validationStates->add($validationState);
            $validationState->setState($this);
        }

        return $this;
    }

    public function removeValidationState(ValidationState $validationState): self
    {
        if ($this->validationStates->removeElement($validationState)) {
            // set the owning side to null (unless already changed)
            if ($validationState->getState() === $this) {
                $validationState->setState(null);
            }
        }

        return $this;
    }
}
