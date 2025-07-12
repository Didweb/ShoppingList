<?php

namespace App\Entity;

use App\ValueObject\HexColor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CircleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: CircleRepository::class)]
class Circle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'hex_color', nullable: false)]
    private HexColor $color;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $qr = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'circles')]
    #[ORM\JoinTable(name: 'circle_user')]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'circle', targetEntity: ShoppingList::class, cascade: ['persist', 'remove'])]
    private Collection $shoppingLists;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->shoppingLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): HexColor
    {
        return $this->color;
    }

    public function setColor(HexColor $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getQr(): ?string
    {
        return $this->qr;
    }

    public function setQr(?string $qr): static
    {
        $this->qr = $qr;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addCircle($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeCircle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ShoppingList>
     */
    public function getShoppingLists(): Collection
    {
        return $this->shoppingLists;
    }

    public function addShoppingList(ShoppingList $shoppingList): static
    {
        if (!$this->shoppingLists->contains($shoppingList)) {
            $this->shoppingLists->add($shoppingList);
            $shoppingList->setCircle($this);
        }

        return $this;
    }

    public function removeShoppingList(ShoppingList $shoppingList): static
    {
        $this->shoppingLists->removeElement($shoppingList);
        return $this;
    }
}
