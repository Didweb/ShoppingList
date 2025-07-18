<?php
namespace App\Tests\Service\Item;

use App\Entity\Item;
use App\DTO\Item\ItemAddDto;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use App\Service\Item\ItemCreateService;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Circle\CircleAccessService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItemCreateServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ItemRepository $itemRepository;
    private UserRepository $userRepository;
    private CircleAccessService $circleAccessService;
    private ItemCreateService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->itemRepository = $container->get(ItemRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->circleAccessService = $container->get(CircleAccessService::class);

        $this->service = new ItemCreateService(
            $this->itemRepository,
            $this->userRepository,
            $this->circleAccessService,
            $this->em
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Item')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testCreateNewItemWhenNoSelectedItemAndNoExistingSlug(): void
    {
        // Creamos un usuario para asignar como creador
        $user = TestEntityFactory::makeUser(1, 'User One', 'user1@test.com');
        $shoppingList = TestEntityFactory::makeShoppingList();

        $this->em->persist($shoppingList);
        $this->em->persist($user);
        $this->em->flush();
        $dto = new ItemAddDto($shoppingList->getId(), 'New Unique Item', null, $user->getId());

        $result = $this->service->createOrGet($dto);

        $this->assertNotNull($result);
        $this->assertSame('New Unique Item', $result->name);

        // Verificamos que el item está en base de datos con el usuario correcto
        $itemInDb = $this->itemRepository->find($result->id);
        $this->assertInstanceOf(Item::class, $itemInDb);
        $this->assertSame('New Unique Item', $itemInDb->getName());
        $this->assertSame($user->getId(), $itemInDb->getCreatedBy()?->getId());
    }

    public function testReturnExistingItemWhenSelectedItemIdProvided(): void
    {
        // Creamos usuario y item persistidos
        $user = TestEntityFactory::makeUser(2, 'User Two', 'user2@test.com');
        $item = TestEntityFactory::makeItem(null, 'Existing Item', $user);

        $this->em->persist($user);
        $this->em->persist($item);
        $this->em->flush();

        $dto = new ItemAddDto($item->getId(), 'Ignored Name', $item->getId(), $user->getId());

        $result = $this->service->createOrGet($dto);

        $this->assertSame($item->getName(), $result->name);
        $this->assertSame($item->getId(), $result->id);
    }

    public function testReturnExistingItemWhenSlugExistsForUser(): void
    {
        $user = TestEntityFactory::makeUser(3, 'User Three', 'user3@test.com');
        $existingItem = TestEntityFactory::makeItem(null, 'Slug Item', $user);

        $this->em->persist($user);
        $this->em->persist($existingItem);
        $this->em->flush();

        $dto = new ItemAddDto();
        $dto->idSelectedItem = null;
        $dto->name = 'Slug Item'; // mismo nombre -> mismo slug
        $dto->idUser = $user->getId();

        $result = $this->service->createOrGet($dto);

        $this->assertSame($existingItem->getName(), $result->name);
        $this->assertSame($existingItem->getId(), $result->id);
    }

    public function testThrowsExceptionWhenSelectedItemNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Item sugerido no encontrado');

        $dto = new ItemAddDto();
        $dto->idSelectedItem = 999999; // ID no existente
        $dto->name = 'Any Name';
        $dto->idUser = 1;

        $this->service->createOrGet($dto);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->purgeData();
        $this->em->close();
    }
}