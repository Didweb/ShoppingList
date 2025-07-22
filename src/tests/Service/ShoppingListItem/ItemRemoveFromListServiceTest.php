<?php
namespace App\Tests\Service\ShoppingListItem;

use App\ValueObject\Status;
use App\Entity\ShoppingListItem;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\EntityNotFoundException;
use App\Repository\ShoppingListRepository;
use App\Repository\ShoppingListItemRepository;
use App\Utils\ShoppingListAccessCheckerInterface;
use App\DTO\ShoppingListItem\ItemRemoveFromListDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\ShoppingListItem\ItemRemoveFromListService;

class ItemRemoveFromListServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ItemRemoveFromListService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $shoppingListRepository = $container->get(ShoppingListRepository::class);
        $shoppingListItemRepository = $container->get(ShoppingListItemRepository::class);

        $accessChecker = new class implements ShoppingListAccessCheckerInterface {
            public function userCanAccessShoppingList($shoppingList, int $userId): bool
            {
                return $shoppingList->getCreatedBy()->getId() === $userId;
            }
        };

        $this->service = new ItemRemoveFromListService(
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

    // public function testDeleteSuccessfully(): void
    // {
    //     // Crear datos base
    //     $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
    //     $this->em->persist($user);

    //     $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
    //     $this->em->persist($circle);

    //     $shoppingList = TestEntityFactory::makeShoppingList(null, 'Lista Test', $user, $circle);
    //     $this->em->persist($shoppingList);

    //     $item = TestEntityFactory::makeItem(1, 'Item Test', $user);
    //     $this->em->persist($item);

    //     $this->em->flush();

    //     // Crear ShoppingListItem
    //     $shoppingListItem = new ShoppingListItem();
    //     $shoppingListItem->setShoppingList($shoppingList);
    //     $shoppingListItem->setItem($item);
    //     $shoppingListItem->setAddedBy($user);
    //     $shoppingListItem->setStatus(new Status(Status::PENDING));
    //     $shoppingListItem->setAddedAt(new \DateTimeImmutable());

    //     $this->em->persist($shoppingListItem);
    //     $this->em->flush();

    //     $dto = new ItemRemoveFromListDto(
    //         $shoppingList->getId(),
    //         $item->getId(),
    //         $user->getId()
    //     );

    //     // Ejecutar delete
    //     $this->service->delete($dto);

    //     $this->em->clear(); // Limpiar el EM para forzar reload desde DB

    //     // Verificar que el ShoppingListItem fue borrado
    //     $deleted = $this->em->getRepository(ShoppingListItem::class)
    //         ->findOneBy(['shoppingList' => $shoppingList->getId(), 'item' => $item->getId()]);

    //     $this->assertNull($deleted);
    // }

    // public function testDeleteThrowsEntityNotFoundExceptionForShoppingList(): void
    // {
    //     $this->expectException(EntityNotFoundException::class);

    //     $dto = new ItemRemoveFromListDto(9999, 1, 1);
    //     $this->service->delete($dto);
    // }

    // public function testDeleteThrowsUnauthorizedException(): void
    // {
    //     $user1 = TestEntityFactory::makeUser(1, 'User One', 'one@test.com');
    //     $user2 = TestEntityFactory::makeUser(2, 'User Two', 'two@test.com');
    //     $this->em->persist($user1);
    //     $this->em->persist($user2);

    //     $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user1);
    //     $this->em->persist($circle);

    //     $shoppingList = TestEntityFactory::makeShoppingList(null, 'Lista Test', $user1, $circle);
    //     $this->em->persist($shoppingList);

    //     $item = TestEntityFactory::makeItem(1, 'Item Test', $user1);
    //     $this->em->persist($item);

    //     $this->em->flush();

    //     $shoppingListItem = new ShoppingListItem();
    //     $shoppingListItem->setShoppingList($shoppingList);
    //     $shoppingListItem->setItem($item);
    //     $shoppingListItem->setAddedBy($user1);
    //     $shoppingListItem->setStatus(new Status(Status::PENDING));
    //     $shoppingListItem->setAddedAt(new \DateTimeImmutable());

    //     $this->em->persist($shoppingListItem);
    //     $this->em->flush();

    //     $dto = new ItemRemoveFromListDto(
    //         $shoppingList->getId(),
    //         $item->getId(),
    //         $user2->getId() // usuario sin acceso
    //     );

    //     $this->service->delete($dto);
    // }

    // public function testDeleteThrowsEntityNotFoundExceptionForShoppingListItem(): void
    // {
    //     $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
    //     $this->em->persist($user);

    //     $circle = TestEntityFactory::makeCircle(1, 'Circle Test', '#123456', $user);
    //     $this->em->persist($circle);

    //     $shoppingList = TestEntityFactory::makeShoppingList(null, 'Lista Test', $user, $circle);
    //     $this->em->persist($shoppingList);

    //     $this->em->flush();

    //     $dto = new ItemRemoveFromListDto(
    //         $shoppingList->getId(),
    //         9999, // Item inexistente en la lista
    //         $user->getId()
    //     );

    //     $this->expectException(EntityNotFoundException::class);

    //     $this->service->delete($dto);
    // }
}