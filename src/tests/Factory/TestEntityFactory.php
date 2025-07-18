<?php
namespace App\Tests\Factory;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\Circle;
use App\ValueObject\Slug;
use App\Entity\ShoppingList;
use App\ValueObject\HexColor;
use Doctrine\Common\Collections\ArrayCollection;

class TestEntityFactory
{

    public static function makeSlug(string $value = 'test-slug'): Slug
    {
        return new Slug($value);
    }

    public static function makeItem(
        ?int $id = null,
        string $name = 'Default Item',
        ?User $createdBy = null,
        ?Slug $slug = null,
        ?Item $canonical = null
    ): Item {
        $item = new Item();

        $ref = new \ReflectionClass($item);

        if ($id !== null) {
            $idProp = $ref->getProperty('id');
            $idProp->setAccessible(true);
            $idProp->setValue($item, $id);
        }

        $item->setName($name);

        if ($slug === null) {
            $slug = new Slug(strtolower(str_replace(' ', '-', $name)));
        }
        $item->setSlug($slug);

        $item->setCreatedBy($createdBy);

        if ($canonical !== null) {
            $item->setCanonical($canonical);
        }

        return $item;
    }


    public static function makeUser(int $id, string $name = 'Test User', string $email = 'info@prueba-test.com'): User
    {
        $user = new User();

        $ref = new \ReflectionClass($user);
        $idProp = $ref->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($user, $id);

        $nameProp = $ref->getProperty('name');
        $nameProp->setAccessible(true);
        $nameProp->setValue($user, $name);

        $nameProp = $ref->getProperty('email');
        $nameProp->setAccessible(true);
        $nameProp->setValue($user, $email);

        $nameProp = $ref->getProperty('password');
        $nameProp->setAccessible(true);
        $nameProp->setValue($user, '12345678');

        return $user;
    }

    public static function makeShoppingList(
        int $id = 1,
        string $name = 'My Shopping List',
        ?User $createdBy = null,
        ?Circle $circle = null
    ): ShoppingList {
        $shoppingList = new ShoppingList();

        $ref = new \ReflectionClass($shoppingList);

        // ID
        $idProp = $ref->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($shoppingList, $id);

        // Name
        $nameProp = $ref->getProperty('name');
        $nameProp->setAccessible(true);
        $nameProp->setValue($shoppingList, $name);

        // createdBy
        if ($createdBy === null) {
            $createdBy = self::makeUser(99, 'Creator User');
        }
        $createdByProp = $ref->getProperty('createdBy');
        $createdByProp->setAccessible(true);
        $createdByProp->setValue($shoppingList, $createdBy);

        // createAt
        $createAtProp = $ref->getProperty('createAt');
        $createAtProp->setAccessible(true);
        $createAtProp->setValue($shoppingList, new \DateTimeImmutable());

        // circle
        if ($circle === null) {
            $circle = self::makeCircle(777, 'Default Circle', '#ABCDEF', $createdBy);
        }
        $circleProp = $ref->getProperty('circle');
        $circleProp->setAccessible(true);
        $circleProp->setValue($shoppingList, $circle);

        return $shoppingList;
    }

    public static function makeCircle(
        int $id,
        string $name,
        string $hexColor,
        User $createdBy,
        array $users = [],
        array $shoppingLists = []
    ): Circle {
        $circle = new Circle();

        $ref = new \ReflectionClass($circle);

        $idProp = $ref->getProperty('id');
        $idProp->setAccessible(true);
        $idProp->setValue($circle, $id);

        $nameProp = $ref->getProperty('name');
        $nameProp->setAccessible(true);
        $nameProp->setValue($circle, $name);

        $colorProp = $ref->getProperty('color');
        $colorProp->setAccessible(true);
        $colorProp->setValue($circle, new HexColor($hexColor));

        $createdByProp = $ref->getProperty('createdBy');
        $createdByProp->setAccessible(true);
        $createdByProp->setValue($circle, $createdBy);

        $usersProp = $ref->getProperty('users');
        $usersProp->setAccessible(true);
        $usersProp->setValue($circle, new ArrayCollection($users));

        $shoppingListsProp = $ref->getProperty('shoppingLists');
        $shoppingListsProp->setAccessible(true);
        $shoppingListsProp->setValue($circle, new ArrayCollection($shoppingLists));

        return $circle;
    }
}