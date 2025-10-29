<?php
namespace App\Tests\Service\ShoppingList;

use App\Entity\User;
use App\Entity\Circle;
use App\Entity\ShoppingList;
use App\Exception\UnauthorizedException;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Utils\CircleAccessCheckerInterface;
use App\DTO\ShoppingList\ShoppingListCreateDto;
use App\Service\ShoppingList\ShoppingListCreateService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingListCreateServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private CircleAccessCheckerInterface $accessChecker;
    private ShoppingListCreateService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->accessChecker = $container->get(CircleAccessCheckerInterface::class);
        // CircleRepository que usa el servicio internamente:
        $circleRepository = $container->get('App\Repository\CircleRepository');

        $this->service = new ShoppingListCreateService(
            $circleRepository,
            $this->em,
            $this->accessChecker
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        // Purga en orden inverso a las dependencias para evitar FK issues
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testCreateShoppingListSuccessfully(): void
    {
        // Creamos usuario y círculo, persistimos
        $user = TestEntityFactory::makeUser(1, 'Test User', 'testuser@example.com');
        $circle = TestEntityFactory::makeCircle(1, 'Test Circle', '#123456', $user);

        $this->em->persist($user);
        $this->em->persist($circle);
        $this->em->flush();

        // DTO con el id del círculo válido
        $dto = new ShoppingListCreateDto('Nueva Lista', $circle->getId());
        $authUser = $this->getAuthUser($user);
     

        // El servicio usa CircleAccessCheckerInterface, que debe permitir el acceso
        // Como usamos la implementación real, aseguramos que devuelva true:
        // Si la implementación es compleja, puedes sobreescribirla con una versión custom
        // Para ejemplo simple, asumimos que el acceso es permitido

        $this->service->create($user, $dto, $authUser);

        // Verificamos que la lista se creó
        $shoppingListRepo = $this->em->getRepository(ShoppingList::class);
        $savedList = $shoppingListRepo->findOneBy(['name' => 'Nueva Lista']);

        $this->assertNotNull($savedList);
        $this->assertSame('Nueva Lista', $savedList->getName());
        $this->assertSame($circle->getId(), $savedList->getCircle()->getId());
        $this->assertSame($user->getId(), $savedList->getCreatedBy()->getId());
        $this->assertInstanceOf(\DateTimeImmutable::class, $savedList->getCreateAt());
    }

    public function testThrowsExceptionWhenCircleNotFound(): void
    {
        $user = TestEntityFactory::makeUser(2, 'User Two', 'user2@example.com');
        $this->em->persist($user);
        $this->em->flush();

        $dto = new ShoppingListCreateDto('Cualquier Lista', 999999);
        $authUser = $this->getAuthUser($user);

        $this->expectException(EntityNotFoundException::class);

        $this->service->create($user, $dto, $authUser);
    }

    public function testThrowsExceptionWhenUserNotAuthorized(): void
    {
        $user = TestEntityFactory::makeUser(3, 'User Three', 'user3@example.com');
        $circleOwner = TestEntityFactory::makeUser(4, 'Circle Owner', 'owner@example.com');
        $circle = TestEntityFactory::makeCircle(1, 'Circle Privado', '#654321', $circleOwner);

        $this->em->persist($user);
        $this->em->persist($circleOwner);
        $this->em->persist($circle);
        $this->em->flush();

        $dto = new ShoppingListCreateDto('Lista Prohibida', $circle->getId());
        $authUser = $this->getAuthUser($user);
        

        // Aquí deberíamos simular el acceso denegado:
        // Como no usamos mocks, una forma simple sería usar un CircleAccessChecker real
        // pero que chequea realmente el acceso. Si es complejo, considera una implementación custom para test.

        // Para forzar que falle, hacemos override manual del checker:

        $serviceWithFailingAccessChecker = new ShoppingListCreateService(
            $this->getContainer()->get('App\Repository\CircleRepository'),
            $this->em,
            new class implements CircleAccessCheckerInterface {
                public function userCanAccessCircle(Circle $circle, AuthenticatedUserInterface $user): bool
                {
                    return false; // siempre deniega acceso para test
                }
            }
        );

        $this->expectException(UnauthorizedException::class);

        $serviceWithFailingAccessChecker->create($user, $dto, $authUser);
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