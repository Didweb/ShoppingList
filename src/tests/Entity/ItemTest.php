<?php
namespace App\Tests\Entity;

use App\Entity\Item;
use App\ValueObject\Slug;
use PHPUnit\Framework\TestCase;
use App\Tests\Factory\TestEntityFactory;

final class ItemTest extends TestCase
{
    public function testFactoryCreatesValidItem(): void
    {
        $user = TestEntityFactory::makeUser(10, 'Creator');
        $item = TestEntityFactory::makeItem(1, 'My Item', $user);

        $this->assertSame('My Item', $item->getName());
        $this->assertSame($user, $item->getCreatedBy());
        $this->assertInstanceOf(Slug::class, $item->getSlug());
    }

    public function testSetNameAndGetName(): void
    {
        $item = new Item();
        $item->setName('My Item Name');

        $this->assertSame('My Item Name', $item->getName());
    }

    public function testSetAndGetCreatedBy(): void
    {
        $user = TestEntityFactory::makeUser(1, 'User Creator');
        $item = new Item();

        $item->setCreatedBy($user);

        $this->assertSame($user, $item->getCreatedBy());

        // Permitir null
        $item->setCreatedBy(null);
        $this->assertNull($item->getCreatedBy());
    }

    public function testSetAndGetSlug(): void
    {
        $slug = TestEntityFactory::makeSlug('custom-slug');
        $item = new Item();

        $item->setSlug($slug);

        $this->assertSame($slug, $item->getSlug());
        $this->assertSame('custom-slug', $item->getSlug()->value());
    }

    public function testSetAndGetCanonical(): void
    {
        $item = new Item();
        $canonical = new Item();
        $canonical->setName('Canonical Item');

        $item->setCanonical($canonical);

        $this->assertSame($canonical, $item->getCanonical());
    }

    public function testGetCanonicalReturnsSelfIfNotSet(): void
    {
        $item = new Item();

        $this->assertSame($item, $item->getCanonical());
    } 
}