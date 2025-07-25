<?php
namespace App\Tests\Service\ShoppingList;

use App\Entity\ShoppingList;
use App\Exception\UnauthorizedException;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\ShoppingList\ShoppingListDto;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\DTO\ShoppingList\ShoppingListEditDto;
use App\Utils\ShoppingListAccessCheckerInterface;
use App\Service\ShoppingList\ShoppingListEditService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingListEditServiceTest extends KernelTestCase
{
    private ShoppingListEditService $service;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);

        $repo = $this->em->getRepository(ShoppingList::class);

        $accessChecker = new class implements ShoppingListAccessCheckerInterface {
            public function userCanAccessShoppingList(ShoppingList $shoppingList, int $userId): bool
            {
                return $shoppingList->getCreatedBy()->getId() === $userId;
            }
        };

        $this->service = new ShoppingListEditService(
            $repo,
            $this->em,
            $accessChecker
        );

        $this->purgeData();
    }


    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testEditShoppingListSuccessfully(): void
    {
        // Arrange: crear entidades
        $user = TestEntityFactory::makeUser(1, 'Test User', 'testuserotro@example.com');
        $circle = TestEntityFactory::makeCircle(1, 'Test Circle', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista original', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        $id = $shoppingList->getId();
        $newName = 'Lista Editada';

        $dto = new ShoppingListEditDto(
            id: $id,
            name: $newName
        );

        $authUser = new class($user->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        // Act
        $result = $this->service->edit($dto, $authUser);

        // Assert
        $this->assertInstanceOf(ShoppingListDto::class, $result);
        $this->assertSame($newName, $result->name);

        $updated = $this->em->getRepository(ShoppingList::class)->find($id);
        $this->assertSame($newName, $updated->getName());

    }


    public function testEditThrowsIfShoppingListNotFound(): void
    {
        $user = TestEntityFactory::makeUser(1, 'Test User', 'testuser@example.com');
        $dto = new ShoppingListEditDto(
            id: 99999,
            name: 'No importa'
        );

        $authUser = new class($user->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $this->expectException(EntityNotFoundException::class);

        $this->service->edit($dto, $authUser);
    }

    public function testEditThrowsIfUnauthorized(): void
    {
        $user = TestEntityFactory::makeUser(1, 'Test User', 'testuser@example.com');
        $userOther = TestEntityFactory::makeUser(1, 'Test User Sin auto', 'testusersinauto@example.com');
        $circle = TestEntityFactory::makeCircle(1, 'Test Circle', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista original', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        $dto = new ShoppingListEditDto(
            id: $shoppingList->getId(),
            name: 'Intento no autorizado'
        );

        $otherUser = new class($userOther->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $this->expectException(UnauthorizedException::class);

        $this->service->edit($dto, $otherUser);
    }

    protected function tearDown(): void
    {
        $this->purgeData();
        $this->em->close();
        parent::tearDown();
    }
}