<?php
namespace App\Tests\Service\ShoppingList;

use App\Entity\User;
use App\Entity\ShoppingList;
use App\Exception\UnauthorizedException;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\DTO\ShoppingList\ShoppingListDeleteDto;
use App\Utils\ShoppingListAccessCheckerInterface;
use App\Service\ShoppingList\ShoppingListDeleteService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingListDeleteServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ShoppingListAccessCheckerInterface $accessChecker;
    private ShoppingListDeleteService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->accessChecker = $container->get(ShoppingListAccessCheckerInterface::class);
        $shoppingListRepository = $container->get('App\Repository\ShoppingListRepository');

        $this->service = new ShoppingListDeleteService(
            $shoppingListRepository,
            $this->em,
            $this->accessChecker
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testDeleteShoppingListSuccessfully(): void
    {
        // Crear usuario, círculo y lista de compras
        $user = TestEntityFactory::makeUser(1, 'Test User', 'testuser@example.com');
        $circle = TestEntityFactory::makeCircle(1, 'Test Circle', '#123456', $user);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista a borrar', $user, $circle);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        $dto = new ShoppingListDeleteDto($shoppingList->getId());
        $authUser = $this->getAuthUser($user);

        // El acceso se asume permitido para este test (usa implementación real)
        $this->service->delete($dto, $authUser);

        $deleted = $this->em->getRepository(ShoppingList::class)->find($dto->id);
        $this->assertNull($deleted, 'La lista de compras fue eliminada');
    }

    public function testThrowsExceptionWhenShoppingListNotFound(): void
    {
        $user = TestEntityFactory::makeUser(2, 'User Two', 'user2@example.com');
        $this->em->persist($user);
        $this->em->flush();

        $dto = new ShoppingListDeleteDto(999999);
        $authUser = $this->getAuthUser($user);

        $this->expectException(EntityNotFoundException::class);

        $this->service->delete($dto, $authUser);
    }

    public function testThrowsExceptionWhenUserNotAuthorized(): void
    {
        $user = TestEntityFactory::makeUser(3, 'User Three', 'user3@example.com');
        $circleOwner = TestEntityFactory::makeUser(4, 'Circle Owner', 'owner@example.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Privado', '#654321', $circleOwner);
        $shoppingList = TestEntityFactory::makeShoppingList(1, 'Lista Prohibida', $circleOwner, $circle);

        $this->em->persist($user);
        $this->em->persist($circleOwner);
        $this->em->persist($circle);
        $this->em->persist($shoppingList);
        $this->em->flush();

        $dto = new ShoppingListDeleteDto($shoppingList->getId());
        $authUser = $this->getAuthUser($user);

        // Override del checker para simular acceso denegado
        $serviceWithFailingAccessChecker = new ShoppingListDeleteService(
            $this->getContainer()->get('App\Repository\ShoppingListRepository'),
            $this->em,
            new class implements ShoppingListAccessCheckerInterface {
                public function userCanAccessShoppingList($shoppingList, int $userId): bool
                {
                    return false; 
                }
            }
        );

        $this->expectException(UnauthorizedException::class);

        $serviceWithFailingAccessChecker->delete($dto, $authUser);
    }

    private function getAuthUser(User $user): AuthenticatedUserInterface
    {
        return new class($user) implements AuthenticatedUserInterface {
            public function __construct(private User $user) {}

            public function getUser(): User
            {
                return $this->user;
            }

            public function getId(): int
            {
                return $this->user->getId();
            }
        };
    }

    protected function tearDown(): void
    {
        $this->purgeData();
        $this->em->close();
        parent::tearDown();
    }
}