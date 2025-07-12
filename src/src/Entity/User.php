<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Circle::class, mappedBy: 'users')]
    private Collection $circles;

    #[ORM\Column(nullable: true)]
    private ?string $resetPasswordToken = null;

    #[ORM\Column(nullable: true, type: 'datetime_immutable')]
    private ?\DateTimeImmutable $resetPasswordExpiresAt = null;

    public function __construct()
    {
        $this->circles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Circle>
     */
    public function getCircles(): Collection
    {
        return $this->circles;
    }

    public function addCircle(Circle $circle): static
    {
        if (!$this->circles->contains($circle)) {
            $this->circles->add($circle);
        }

        return $this;
    }

    public function removeCircle(Circle $circle): static
    {
        $this->circles->removeElement($circle);

        return $this;
    }

     public function getResetPasswordToken(): ?string
    {
        return $this->resetPasswordToken;
    }

    public function setResetPasswordToken(?string $token): self
    {
        $this->resetPasswordToken = $token;
        return $this;
    }

    public function getResetPasswordExpiresAt(): ?\DateTimeImmutable
    {
        return $this->resetPasswordExpiresAt;
    }

    public function setResetPasswordExpiresAt(?\DateTimeImmutable $expiresAt): self
    {
        $this->resetPasswordExpiresAt = $expiresAt;
        return $this;
    }
}
