<?php
namespace App\Entity;

use App\ValueObject\Status;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class ShoppingListItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ShoppingList::class, inversedBy: 'shoppingListItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ShoppingList $shoppingList;

    #[ORM\ManyToOne(targetEntity: Item::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Item $item;

    #[ORM\Column(type: 'status')]
    private Status $status; 

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $addedBy;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $addedAt;

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShoppingList(): ShoppingList
    {
        return $this->shoppingList;
    }

    public function setShoppingList(ShoppingList $shoppingList): static
    {
        $this->shoppingList = $shoppingList;
        return $this;
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function setItem(Item $item): static
    {
        $this->item = $item;
        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;
        return $this;
    }

    public function getAddedBy(): User
    {
        return $this->addedBy;
    }

    public function setAddedBy(User $addedBy): static
    {
        $this->addedBy = $addedBy;
        return $this;
    }

    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function setAddedAt(\DateTimeImmutable $addedAt): static
    {
        $this->addedAt = $addedAt;
        return $this;
    }
}