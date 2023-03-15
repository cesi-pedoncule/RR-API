<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV6 as Uuid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\User\GetCurrentUserController;
use App\Controller\User\DeleteUserController;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    formats: ['json'], 
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    operations: [
        new GetCollection(
            name: 'current_user_get',
            uriTemplate: '/users/me',
            normalizationContext: ['groups' => ['user:me']],
            controller: GetCurrentUserController::class,
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only authenticated users can access this resource.',
        ),
        new Get(uriTemplate: '/users/{id}', name: 'user_get', normalizationContext: ['groups' => ['user:read']]),
        new Post(
            uriTemplate: '/users',
            name: 'user_post',
            write: true,
        ),
        new GetCollection(
            uriTemplate: '/users', 
        ),
        new Put(
            uriTemplate: '/users/{id}',
            name: 'user_put',
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only authenticated users can access this resource.',
        ),
        new Delete(
            uriTemplate: '/users/{id}', 
            name: 'user_delete', 
            controller: DeleteUserController::class,
            security: 'is_granted("ROLE_ADMIN") or object == user',
            securityMessage: 'Only the current user or an admin can delete it.',
        ),
    ],
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user:read', 'user:me', 'resource:read', 'category:read', 'validationState:read', 'comment:read', 'user_like:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:read', 'user:me', 'resource:read', 'user:write', 'validationState:read', 'comment:read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:me', 'resource:read', 'validationState:read', 'comment:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:me', 'resource:read', 'user:write', 'category:read', 'validationState:read', 'comment:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:me', 'resource:read', 'user:write', 'category:read', 'validationState:read', 'comment:read'])]
    private ?string $firstname = null;

    #[ORM\Column]
    #[Groups(['user:read', 'user:me', 'resource:read', 'validationState:read', 'comment:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user:read', 'user:me', 'resource:read', 'validationState:read', 'comment:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['user:read', 'user:me', 'resource:read', 'validationState:read', 'comment:read'])]
    private ?bool $isBanned = false;

    #[ORM\OneToMany(mappedBy: 'User', targetEntity: Attachment::class)]
    private Collection $attachments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Resource::class)]
    #[Groups(['user:read', 'user:me',])]
    private Collection $resources;

    #[ORM\OneToMany(mappedBy: 'moderator', targetEntity: ValidationState::class)]
    private Collection $validationStates;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserLike::class)]
    #[Groups(['user:read', 'user:me'])]
    private Collection $resourceLikes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFollow::class)]
    #[Groups(['user:read', 'user:me'])]
    private Collection $userFollows;

    #[ORM\OneToMany(mappedBy: 'follower', targetEntity: UserFollow::class)]
    #[Groups(['user:read', 'user:me'])]
    private Collection $userFollowers;

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->password = $this->hashPassword($this->password);
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        // TODO : if the password change hash it
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->validationStates = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->resourceLikes = new ArrayCollection();
        $this->userFollows = new ArrayCollection();
        $this->userFollowers = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setUser($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getUser() === $this) {
                $attachment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setUser($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getUser() === $this) {
                $resource->setUser(null);
            }
        }

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
            $validationState->setModerator($this);
        }

        return $this;
    }

    public function removeValidationState(ValidationState $validationState): self
    {
        if ($this->validationStates->removeElement($validationState)) {
            // set the owning side to null (unless already changed)
            if ($validationState->getModerator() === $this) {
                $validationState->setModerator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setCreator($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCreator() === $this) {
                $category->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLike>
     */
    public function getResourceLikes(): Collection
    {
        return $this->resourceLikes;
    }

    public function addResourceLike(UserLike $resourceLike): self
    {
        if (!$this->resourceLikes->contains($resourceLike)) {
            $this->resourceLikes->add($resourceLike);
            $resourceLike->setUser($this);
        }

        return $this;
    }

    public function removeResourceLike(UserLike $resourceLike): self
    {
        if ($this->resourceLikes->removeElement($resourceLike)) {
            // set the owning side to null (unless already changed)
            if ($resourceLike->getUser() === $this) {
                $resourceLike->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFollow>
     */
    public function getUserFollows(): Collection
    {
        return $this->userFollows;
    }

    public function addUserFollow(UserFollow $userFollow): self
    {
        if (!$this->userFollows->contains($userFollow)) {
            $this->userFollows->add($userFollow);
            $userFollow->setUser($this);
        }

        return $this;
    }

    public function removeUserFollow(UserFollow $userFollow): self
    {
        if ($this->userFollows->removeElement($userFollow)) {
            // set the owning side to null (unless already changed)
            if ($userFollow->getUser() === $this) {
                $userFollow->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFollow>
     */
    public function getUserFollowers(): Collection
    {
        return $this->userFollowers;
    }

    public function addUserFollower(UserFollow $userFollower): self
    {
        if (!$this->userFollowers->contains($userFollower)) {
            $this->userFollowers->add($userFollower);
            $userFollower->setFollower($this);
        }

        return $this;
    }

    public function removeUserFollower(UserFollow $userFollower): self
    {
        if ($this->userFollowers->removeElement($userFollower)) {
            // set the owning side to null (unless already changed)
            if ($userFollower->getFollower() === $this) {
                $userFollower->setFollower(null);
            }
        }

        return $this;
    }
}
