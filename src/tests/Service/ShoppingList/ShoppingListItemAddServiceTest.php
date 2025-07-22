<?php
namespace App\Tests\Service\ShoppingList;

use App\Entity\Item;
use App\Entity\User;
use App\ValueObject\Status;
use App\Entity\ShoppingList;
use App\Entity\ShoppingListItem;
use App\DTO\Item\ItemAddToListDto;
use App\Exception\UnauthorizedException;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\ShoppingListItemRepository;
use App\Exception\DuplicateNotAllowedException;
use App\Utils\ShoppingListAccessCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\ShoppingList\ShoppingListItemAddService;

class ShoppingListItemAddServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ShoppingListItemAddService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);

        $shoppingListRepository = $this->em->getRepository(ShoppingList::class);
        $shoppingListItemRepository = $container->get(ShoppingListItemRepository::class);
        $itemRepository = $this->em->getRepository(Item::class);
        $userRepository = $this->em->getRepository(User::class);
        $security = $container->get(Security::class);

        $accessChecker = new class implements ShoppingListAccessCheckerInterface {
            public function userCanAccessShoppingList(ShoppingList $shoppingList, int $userId): bool
            {
                return $shoppingList->getCreatedBy()->getId() === $userId;
            }
        };

        $this->service = new ShoppingListItemAddService(
            $shoppingListRepository,
            $shoppingListItemRepository,
            $itemRepository,
            $userRepository,
            $this->em,
            $accessChecker,
            $security
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        // Borra dependientes primero
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingListItem')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Item')->execute();
        $this->em->flush();
    }

    public function testAddAddsItemToShoppingList(): void
    {
        $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Test', $user, $circle);
        $item = TestEntityFactory::makeItem(1, 'Item Test');

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->persist($item);
        $this->em->flush();

        $dto = new ItemAddToListDto(
            $shoppingList->getId(),
            null,
            $item->getId(),
            $user->getId()
        );

        $result = $this->service->add($dto);

        $this->assertEquals($shoppingList->getId(), $result->id);
        $this->assertEquals('Lista Test', $result->name);
    }

    public function testAddThrowsEntityNotFoundIfShoppingListDoesNotExist(): void
    {
        $dto = new ItemAddToListDto(
            9999,
            null,
            1,
            1
        );

        $this->expectException(EntityNotFoundException::class);
        $this->service->add($dto);
    }

    public function testAddThrowsEntityNotFoundIfItemDoesNotExist(): void
    {
        $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Test', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        $dto = new ItemAddToListDto(
            $shoppingList->getId(),
            null,
            9999,
            $user->getId()
        );

        $this->expectException(EntityNotFoundException::class);
        $this->service->add($dto);
    }

    public function testAddThrowsEntityNotFoundIfUserDoesNotExist(): void
    {
        $item = TestEntityFactory::makeItem(1, 'Item Test');

        $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Test', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->persist($item);
        $this->em->flush();

        $dto = new ItemAddToListDto(
            $shoppingList->getId(),
            null,
            $item->getId(),
            9999
        );

        $this->expectException(UnauthorizedException::class);
        $this->service->add($dto);
    }

    public function testAddThrowsUnauthorizedWhenUserCannotAccess(): void
    {
        $owner = TestEntityFactory::makeUser(1, 'Owner', 'owner@test.com');
        $intruder = TestEntityFactory::makeUser(2, 'Intruder', 'intruder@test.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $owner);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Test', $owner, $circle);
        $item = TestEntityFactory::makeItem(1, 'Item Test');

        $this->em->persist($owner);
        $this->em->persist($intruder);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->persist($item);
        $this->em->flush();

        $dto = new ItemAddToListDto(
            $shoppingList->getId(),
            null,
            $item->getId(),
            $intruder->getId()
        );

        $this->expectException(UnauthorizedException::class);
        $this->service->add($dto);
    }

    public function testAddThrowsDuplicateNotAllowedIfItemAlreadyExists(): void
    {
        $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Test', $user, $circle);
        $item = TestEntityFactory::makeItem(1, 'Item Test');

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->persist($item);

        // Ya existe un item en la lista
        $shoppingListItem = new ShoppingListItem();
        $shoppingListItem->setShoppingList($shoppingList);
        $shoppingListItem->setItem($item);
        $shoppingListItem->setAddedBy($user);
        $shoppingListItem->setStatus(new Status(Status::PENDING));
        $shoppingListItem->setAddedAt(new \DateTimeImmutable());

        $this->em->persist($shoppingListItem);
        $this->em->flush();

        $dto = new ItemAddToListDto(
            $shoppingList->getId(),
            null,
            $item->getId(),
            $user->getId()
        );

        $this->expectException(DuplicateNotAllowedException::class);
        $this->service->add($dto);
    }
}