<?php

namespace App\Entity;

use App\ValueObject\Slug;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ItemRepository;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $name;

    #[ORM\Column(type: 'slug')]
    private Slug $slug;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $createdBy = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Item $canonicalItem = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $user): static
    {
        $this->createdBy = $user;

        return $this;
    }

    public function getSlug(): Slug
    {
        return $this->slug;
    }


    public function setSlug(Slug $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setCanonical(Item $canonical): void
    {
        $this->canonicalItem = $canonical;
    }

    public function getCanonical(): Item
    {
        return $this->canonicalItem ?? $this;
    }
}
