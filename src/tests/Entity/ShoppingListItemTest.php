<?php
namespace App\Tests\Entity;

use App\Entity\Item;
use App\Entity\User;
use App\ValueObject\Status;
use App\Entity\ShoppingList;
use PHPUnit\Framework\TestCase;
use App\Entity\ShoppingListItem;

class ShoppingListItemTest extends TestCase
{
    public function testInitialState(): void
    {
        $shoppingListItem = new ShoppingListItem();

        // Solo ID puede ser null, los demás deben lanzarse o estar sin inicializar
        $this->assertNull($shoppingListItem->getId());

        $this->expectException(\Error::class);
        $shoppingListItem->getShoppingList();

        $this->expectException(\Error::class);
        $shoppingListItem->getItem();

        $this->expectException(\Error::class);
        $shoppingListItem->getStatus();

        $this->expectException(\Error::class);
        $shoppingListItem->getAddedBy();

        $this->expectException(\Error::class);
        $shoppingListItem->getAddedAt();
    }

    public function testSetAndGetShoppingList(): void
    {
        $shoppingList = new ShoppingList();
        $shoppingListItem = new ShoppingListItem();

        $result = $shoppingListItem->setShoppingList($shoppingList);

        $this->assertSame($shoppingList, $shoppingListItem->getShoppingList());
        $this->assertSame($shoppingListItem, $result);
    }

    public function testSetAndGetItem(): void
    {
        $item = new Item();
        $shoppingListItem = new ShoppingListItem();

        $result = $shoppingListItem->setItem($item);

        $this->assertSame($item, $shoppingListItem->getItem());
        $this->assertSame($shoppingListItem, $result);
    }

    public function testSetAndGetStatus(): void
    {
        $status = new Status('pending');
        $shoppingListItem = new ShoppingListItem();

        $result = $shoppingListItem->setStatus($status);

        $this->assertSame($status, $shoppingListItem->getStatus());
        $this->assertSame($shoppingListItem, $result);
    }

    public function testSetAndGetAddedBy(): void
    {
        $user = new User();
        $shoppingListItem = new ShoppingListItem();

        $result = $shoppingListItem->setAddedBy($user);

        $this->assertSame($user, $shoppingListItem->getAddedBy());
        $this->assertSame($shoppingListItem, $result);
    }

    public function testSetAndGetAddedAt(): void
    {
        $date = new \DateTimeImmutable('now');
        $shoppingListItem = new ShoppingListItem();

        $result = $shoppingListItem->setAddedAt($date);

        $this->assertSame($date, $shoppingListItem->getAddedAt());
        $this->assertSame($shoppingListItem, $result);
    }

    public function testFluentInterface(): void
    {
        $shoppingList = new ShoppingList();
        $item = new Item();
        $status = new Status('pending');
        $user = new User();
        $date = new \DateTimeImmutable();

        $shoppingListItem = new ShoppingListItem();
        $shoppingListItem
            ->setShoppingList($shoppingList)
            ->setItem($item)
            ->setStatus($status)
            ->setAddedBy($user)
            ->setAddedAt($date);

        $this->assertSame($shoppingList, $shoppingListItem->getShoppingList());
        $this->assertSame($item, $shoppingListItem->getItem());
        $this->assertSame($status, $shoppingListItem->getStatus());
        $this->assertSame($user, $shoppingListItem->getAddedBy());
        $this->assertSame($date, $shoppingListItem->getAddedAt());
    }
}