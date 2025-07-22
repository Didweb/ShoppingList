<?php
namespace App\Tests\Service\ShoppingListItem;

use App\Entity\Item;
use App\ValueObject\Status;
use App\Entity\ShoppingList;
use App\Entity\ShoppingListItem;
use App\Exception\UnauthorizedException;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use App\Repository\ShoppingListItemRepository;
use App\DTO\ShoppingListItem\ItemChangeStatusDto;
use App\Utils\ShoppingListAccessCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\ShoppingListItem\ItemChangeStatusService;

class ItemChangeStatusServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ItemChangeStatusService $service;

    protected function setUp(): void
    {
       
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);

        // $shoppingListRepository = $this->em->getRepository(ShoppingList::class);
        // $shoppingListItemRepository = $this->em->getRepository(ShoppingListItem::class);
        $shoppingListRepository = $container->get(ShoppingListRepository::class);
        $shoppingListItemRepository = $container->get(ShoppingListItemRepository::class);

        $accessChecker = new class implements ShoppingListAccessCheckerInterface {
            public function userCanAccessShoppingList(ShoppingList $shoppingList, int $userId): bool
            {
                return $shoppingList->getCreatedBy()->getId() === $userId;
            }
        };

        $this->service = new ItemChangeStatusService(
            $shoppingListItemRepository,
            $shoppingListRepository,
            $accessChecker,
            $this->em
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingListItem')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Item')->execute();
        $this->em->flush();
    }

    // public function testChangeStatusSuccessfully(): void
    // {
    //     // Crear datos base
    //     $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
    //     $this->em->persist($user);
    //     $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
    //     $this->em->persist($circle);
    //     $shoppingList = TestEntityFactory::makeShoppingList(null, 'Lista Test', $user, $circle);
    //     $item = TestEntityFactory::makeItem(1, 'Item Test', $user);

    //     $this->em->persist($shoppingList);
    //     $this->em->flush();
    //     $this->em->persist($item);
    //     $this->em->flush();

    //     $shoppingListItem = new ShoppingListItem();
    //     $shoppingListItem->setShoppingList($shoppingList);
    //     $shoppingListItem->setItem($item);
    //     $shoppingListItem->setAddedBy($user);
    //     $shoppingListItem->setStatus(new Status(Status::PENDING));
    //     $shoppingListItem->setAddedAt(new \DateTimeImmutable());

    //     $this->em->persist($shoppingListItem);
    //     $this->em->flush();


    //     $dto = new ItemChangeStatusDto(
    //         $shoppingList->getId(),
    //         $item->getId(),
    //         Status::PURCHASED,
    //         $user->getId()
    //     );

    //     $resultDto = $this->service->change($dto);

    //     $this->assertEquals(Status::PURCHASED, $resultDto->status);
    // }

    public function testThrowsIfShoppingListNotFound(): void
    {
        $dto = new ItemChangeStatusDto(
            9999,  // Id shoppingList no existente
            1,
            Status::PURCHASED,
            1
        );

        $this->expectException(EntityNotFoundException::class);
        $this->service->change($dto);
    }

    public function testThrowsIfUserHasNoAccess(): void
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

        $shoppingListItem = new ShoppingListItem();
        $shoppingListItem->setShoppingList($shoppingList);
        $shoppingListItem->setItem($item);
        $shoppingListItem->setAddedBy($owner);
        $shoppingListItem->setStatus(new Status(Status::PENDING));
        $shoppingListItem->setAddedAt(new \DateTimeImmutable());

        $this->em->persist($shoppingListItem);
        $this->em->flush();

        $dto = new ItemChangeStatusDto(
            $shoppingList->getId(),
            $item->getId(),
            Status::PURCHASED,
            $intruder->getId()
        );

        $this->expectException(EntityNotFoundException::class);
        $this->service->change($dto);
    }

    public function testThrowsIfShoppingListItemNotFound(): void
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

        $dto = new ItemChangeStatusDto(
            $shoppingList->getId(),
            $item->getId(),
            Status::PURCHASED,
            $user->getId()
        );

        $this->expectException(EntityNotFoundException::class);
        $this->service->change($dto);
    }
}