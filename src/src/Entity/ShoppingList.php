<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShoppingListRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ShoppingListRepository::class)]
class ShoppingList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(targetEntity: Circle::class, inversedBy: 'shoppingLists')]
    #[ORM\JoinColumn(nullable: false)]
    private Circle $circle;

    #[ORM\OneToMany(mappedBy: 'shoppingList', targetEntity: ShoppingListItem::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $shoppingListItems;

    public function __construct()
    {
        $this->shoppingListItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

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

    public function getCircle(): Circle
    {
        return $this->circle;
    }

    public function setCircle(Circle $circle): static
    {
        $this->circle = $circle;

        return $this;
    }

    public function getShoppingListItems(): Collection
    {
        return $this->shoppingListItems;
    }

    public function addShoppingListItem(ShoppingListItem $item): static
    {
        if (!$this->shoppingListItems->contains($item)) {
            $this->shoppingListItems->add($item);
            $item->setShoppingList($this);
        }

        return $this;
    }

    public function removeShoppingListItem(ShoppingListItem $item): static
    {
        $this->shoppingListItems->removeElement($item);
        return $this;
    }
}
