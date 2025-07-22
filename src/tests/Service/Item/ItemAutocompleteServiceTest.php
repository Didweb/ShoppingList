<?php
namespace App\Tests\Service\Item;

use App\Entity\Item;
use App\ValueObject\Slug;
use App\DTO\Item\ItemPartialDto;
use App\Repository\ItemRepository;
use App\DTO\Item\ItemSuggestionDto;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Circle\CircleAccessService;
use App\Service\Item\ItemAutocompleteService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItemAutocompleteServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private ItemRepository $itemRepository;
    private CircleAccessService $circleAccessService;
    private ItemAutocompleteService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->itemRepository = $container->get(ItemRepository::class);

        $this->circleAccessService = $this->createMock(CircleAccessService::class);

        $this->service = new ItemAutocompleteService(
            $this->itemRepository,
            $this->circleAccessService
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Item')->execute();
    }

    public function testSuggestReturnsExpectedMatches(): void
    {
        // Creamos Items con la factoría
        $user = TestEntityFactory::makeUser(1, 'User Test', 'info-uno@test.com');
        $item1 = TestEntityFactory::makeItem(null, 'Test Apple', $user);
        $item2 = TestEntityFactory::makeItem(null, 'Test Banana', $user);

        $this->em->persist($user);
        $this->em->persist($item1);
        $this->em->persist($item2);
        $this->em->flush();

        // Mock de CircleAccessService: user tiene acceso a ownerId 1
        $this->circleAccessService
            ->method('getAllowedOwnerIds')
            ->willReturn([$user->getId()]);

        $dto = new ItemPartialDto(partial: 'apple', idUser: 123);

        $result = $this->service->suggest($dto);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertInstanceOf(ItemSuggestionDto::class, $result[0]);
        $this->assertSame('Test Apple', $result[0]->suggestion);
    }

    public function testSuggestFallbackReturnsClosestIfNoMatches(): void
    {
        $user = TestEntityFactory::makeUser(2, 'User Test 2', 'info-dos@test.com');
        $item = TestEntityFactory::makeItem(null, 'Mango', $user);

        $this->em->persist($user);
        $this->em->persist($item);
        $this->em->flush();

        // Mock: usuario no tiene acceso a ownerId 2, así fuerza fallback
        $this->circleAccessService
            ->method('getAllowedOwnerIds')
            ->willReturn([]);

        $dto = new ItemPartialDto(partial: 'mang', idUser: 999);

        $result = $this->service->suggest($dto);

        $this->assertIsArray($result);
        $this->assertNotEmpty($result);
        $this->assertSame('Mango', $result[0]->suggestion);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->purgeData();
        $this->em->close();
    }
}