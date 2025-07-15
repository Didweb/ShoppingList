<?php
namespace App\Tests\Entity;

use App\Entity\Circle;
use App\ValueObject\HexColor;
use PHPUnit\Framework\TestCase;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\Common\Collections\Collection;

final class CircleTest extends TestCase
{
    public function testInitialCollectionsAreEmpty(): void
    {
        $circle = new Circle();

        $this->assertInstanceOf(Collection::class, $circle->getUsers());
        $this->assertCount(0, $circle->getUsers());

        $this->assertInstanceOf(Collection::class, $circle->getShoppingLists());
        $this->assertCount(0, $circle->getShoppingLists());
    }

    public function testSetNameAndGetName(): void
    {
        $circle = new Circle();
        $circle->setName('My Circle');

        $this->assertSame('My Circle', $circle->getName());
    }

    public function testSetColorAndGetColor(): void
    {
        $circle = new Circle();
        $color = new HexColor('#FF0000');

        $circle->setColor($color);

        $this->assertSame($color, $circle->getColor());
    }

    public function testSetQrAndGetQr(): void
    {
        $circle = new Circle();
        $circle->setQr('some-qr-code');

        $this->assertSame('some-qr-code', $circle->getQr());

        $circle->setQr(null);
        $this->assertNull($circle->getQr());
    }

    public function testSetCreatedByAndGetCreatedBy(): void
    {
        $circle = new Circle();
        $user = TestEntityFactory::makeUser(1, 'Creator User');

        $circle->setCreatedBy($user);

        $this->assertSame($user, $circle->getCreatedBy());
    }

    public function testAddUserAddsUserAndUpdatesCollections(): void
    {
        $circle = new Circle();

        $user = TestEntityFactory::makeUser(1, 'Test User');

        $this->assertCount(0, $circle->getUsers());

        $circle->addUser($user);

        $this->assertCount(1, $circle->getUsers());
        $this->assertTrue($circle->getUsers()->contains($user));

        // Añadir el mismo usuario no duplica
        $circle->addUser($user);
        $this->assertCount(1, $circle->getUsers());
    }

    public function testRemoveUserRemovesUserFromCollection(): void
    {
        $circle = new Circle();

        $user = TestEntityFactory::makeUser(2, 'User to Remove');

        $circle->addUser($user);
        $this->assertCount(1, $circle->getUsers());

        $circle->removeUser($user);
        $this->assertCount(0, $circle->getUsers());
    }

    public function testAddShoppingListAddsShoppingListAndSetsCircle(): void
    {
        $createdBy = TestEntityFactory::makeUser(3, 'Creator');
        $circle = TestEntityFactory::makeCircle(10, 'Circle Name', '#123456', $createdBy);

        $shoppingList = TestEntityFactory::makeShoppingList(5, 'List 1', $createdBy, $circle);

        $this->assertCount(0, $circle->getShoppingLists());

        $circle->addShoppingList($shoppingList);

        $this->assertCount(1, $circle->getShoppingLists());
        $this->assertTrue($circle->getShoppingLists()->contains($shoppingList));

        // Añadir duplicado no lo agrega
        $circle->addShoppingList($shoppingList);
        $this->assertCount(1, $circle->getShoppingLists());
    }

    public function testRemoveShoppingListRemovesFromCollection(): void
    {
        $createdBy = TestEntityFactory::makeUser(4, 'Creator');
        $circle = TestEntityFactory::makeCircle(11, 'Circle Name 2', '#654321', $createdBy);

        $shoppingList = TestEntityFactory::makeShoppingList(6, 'List to Remove', $createdBy, $circle);

        $circle->addShoppingList($shoppingList);
        $this->assertCount(1, $circle->getShoppingLists());

        $circle->removeShoppingList($shoppingList);
        $this->assertCount(0, $circle->getShoppingLists());
    }
}