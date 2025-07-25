<?php
namespace App\Tests\Service\ShoppingList;

use App\Entity\ShoppingList;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\DTO\ShoppingList\ShoppingListSimpleDto;
use App\Service\ShoppingList\ShoppingListGetAllService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShoppingListGetAllServiceTest extends KernelTestCase
{
    private ShoppingListGetAllService $service;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);

        /** @var ShoppingListRepository $repo */
        $repo = $this->em->getRepository(ShoppingList::class);

        $this->service = new ShoppingListGetAllService(
            $repo,
            $this->em
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\ShoppingList')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testGetAllReturnsOnlyUserShoppingLists(): void
    {
        // Arrange: 2 usuarios, cada uno con listas
        $user1 = TestEntityFactory::makeUser(1, 'User One', 'one@example.com');
        $user2 = TestEntityFactory::makeUser(2, 'User Two', 'two@example.com');

        $circle1 = TestEntityFactory::makeCircle(1, 'Circle 1', '#111111', $user1);
        $circle2 = TestEntityFactory::makeCircle(2, 'Circle 2', '#222222', $user2);

        $list1 = TestEntityFactory::makeShoppingList(1, 'Lista U1-A', $user1, $circle1);
        $list2 = TestEntityFactory::makeShoppingList(2, 'Lista U1-B', $user1, $circle1);
        $list3 = TestEntityFactory::makeShoppingList(3, 'Lista U2', $user2, $circle2);

        $this->em->persist($user1);
        $this->em->persist($user2);
        $this->em->persist($circle1);
        $this->em->persist($circle2);
        $this->em->persist($list1);
        $this->em->persist($list2);
        $this->em->persist($list3);

        $this->em->flush();

        // Usuario autenticado: user1
        $authUser = new class($user1->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        // Act
        $result = $this->service->get($authUser);

        // Assert
        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        foreach ($result as $dto) {
            $this->assertInstanceOf(ShoppingListSimpleDto::class, $dto);
            $this->assertTrue(in_array($dto->name, ['Lista U1-A', 'Lista U1-B']));
        }
    }
}