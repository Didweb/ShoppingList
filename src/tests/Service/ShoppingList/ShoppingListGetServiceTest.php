<?php
namespace App\Tests\Service\ShoppingList;

use App\Entity\ShoppingList;
use App\Exception\UnauthorizedException;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\ShoppingList\ShoppingListDto;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\DTO\ShoppingList\ShoppingListGetDto;
use App\Utils\ShoppingListAccessCheckerInterface;
use App\Service\ShoppingList\ShoppingListGetService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingListGetServiceTest extends KernelTestCase
{
    private ShoppingListGetService $service;
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

        $this->service = new ShoppingListGetService(
            $repo,
            $this->em,
            $accessChecker
        );

        $this->purgeData();
    }


    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingListItem')->execute();
        $this->em->flush();
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->flush();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->flush();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->flush();
    }

    public function testGetReturnsShoppingListIfUserHasAccess(): void
    {
        // Arrange
        $user = TestEntityFactory::makeUser(1, 'User Test', 'user@test.com');
        $circle = TestEntityFactory::makeCircle(600, 'Circle Test', '#999999', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Test', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        // Simula acceso permitido
        $authUser = new class($user->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $dto = new ShoppingListGetDto($shoppingList->getId(), $user->getId());

        // Act
        $result = $this->service->get($dto);

        // Assert
        $this->assertInstanceOf(ShoppingListDto::class, $result);
        $this->assertEquals('Lista Test', $result->name);
        $this->assertEquals($shoppingList->getId(), $result->id);
    }

    public function testGetThrowsNotFoundWhenListDoesNotExist(): void
    {
        $dto = new ShoppingListGetDto(9999, 1);

        $this->expectException(EntityNotFoundException::class);

        $this->service->get($dto);
    }

    public function testGetThrowsUnauthorizedWhenUserCannotAccess(): void
    {
        // Arrange: lista real
        $user = TestEntityFactory::makeUser(1, 'User X', 'x@test.com');
        $userNotAccess = TestEntityFactory::makeUser(500, 'User XY', 'xy@test.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle X', '#ABCDEF', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista X', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        // Simula acceso denegado
        $authUser = new class($userNotAccess->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $dto = new ShoppingListGetDto($shoppingList->getId(), $userNotAccess->getId());

        $this->expectException(UnauthorizedException::class);

        $this->service->get($dto);
    }
}