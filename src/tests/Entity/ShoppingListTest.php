<?php
namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Circle;
use App\Entity\ShoppingList;
use PHPUnit\Framework\TestCase;
use App\Entity\ShoppingListItem;
use Doctrine\Common\Collections\ArrayCollection;

class ShoppingListTest extends TestCase
{
    public function testInitialState(): void
    {
        $shoppingList = new ShoppingList();

        $this->assertNull($shoppingList->getId());
        $this->assertNull($shoppingList->getName());
        $this->assertNull($shoppingList->getCreatedBy());
        $this->assertNull($shoppingList->getCreateAt());

        $items = $shoppingList->getShoppingListItems();
        $this->assertInstanceOf(ArrayCollection::class, $items);
        $this->assertCount(0, $items);
    }

    public function testSetName(): void
    {
        $shoppingList = new ShoppingList();

        $shoppingList->setName('Groceries');
        $this->assertSame('Groceries', $shoppingList->getName());

        // Prueba que setName admite null (según definición puede ser nullable)
        $shoppingList->setName(null);
        $this->assertNull($shoppingList->getName());
    }

    public function testSetCreatedBy(): void
    {
        $user = $this->createMock(User::class);

        $shoppingList = new ShoppingList();
        $shoppingList->setCreatedBy($user);

        $this->assertSame($user, $shoppingList->getCreatedBy());
    }

    public function testSetCreateAt(): void
    {
        $date = new \DateTimeImmutable();

        $shoppingList = new ShoppingList();
        $shoppingList->setCreateAt($date);

        $this->assertSame($date, $shoppingList->getCreateAt());
    }

    public function testSetCircle(): void
    {
        $circle = $this->createMock(Circle::class);

        $shoppingList = new ShoppingList();
        $shoppingList->setCircle($circle);

        $this->assertSame($circle, $shoppingList->getCircle());
    }

    public function testShoppingListItemsCollection(): void
    {
        $shoppingList = new ShoppingList();
        $item = $this->createMock(ShoppingListItem::class);

        // Inicialmente vacía
        $this->assertCount(0, $shoppingList->getShoppingListItems());

        // Añadir item
        $shoppingList->addShoppingListItem($item);
        $this->assertCount(1, $shoppingList->getShoppingListItems());
        $this->assertTrue($shoppingList->getShoppingListItems()->contains($item));

        // Remover item
        $shoppingList->removeShoppingListItem($item);
        $this->assertCount(0, $shoppingList->getShoppingListItems());
        $this->assertFalse($shoppingList->getShoppingListItems()->contains($item));
    }
}