<?php
namespace App\Tests\Service\Circle;

use PHPUnit\Framework\TestCase;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Service\Circle\CircleGetAllService;

class CircleGetAllServiceTest extends TestCase
{
    private CircleRepository $circleRepository;
    private EntityManagerInterface $em;
    private CircleQrService $qrService;
    private CircleGetAllService $service;

    protected function setUp(): void
    {
        $this->circleRepository = new class extends CircleRepository {
            private array $circles = [];

            public function setCircles(array $circles): void
            {
                $this->circles = $circles;
            }

            public function findByOwnerOrMembership(int $userId): array
            {
                return array_filter($this->circles, function ($circle) use ($userId) {
                    return $circle->getCreatedBy()->getId() === $userId
                        || in_array($userId, array_map(fn($u) => $u->getId(), $circle->getUsers()->toArray()));
                });
            }
        };

        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->qrService = $this->createMock(CircleQrService::class);

        $this->service = new CircleGetAllService(
            $this->circleRepository,
            $this->em,
            $this->qrService
        );
    }

    public function testReturnsAllOwnedCircles(): void
    {
        $owner = TestEntityFactory::makeUser(1);

        $circle1 = TestEntityFactory::makeCircle(10, 'Circle 1', '#123456', $owner);
        $circle2 = TestEntityFactory::makeCircle(20, 'Circle 2', '#654321', $owner);

        $this->circleRepository->setCircles([$circle1, $circle2]);

        $authUser = new class($owner->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $result = $this->service->get($authUser);

        $this->assertCount(2, $result);
        $this->assertEquals('Circle 1', $result[0]->name);
        $this->assertEquals('Circle 2', $result[1]->name);
    }

    public function testReturnsOnlyCirclesUserIsMemberOf(): void
    {
        $owner = TestEntityFactory::makeUser(1);
        $member = TestEntityFactory::makeUser(2);

        $circle1 = TestEntityFactory::makeCircle(10, 'Owned Circle', '#ABCDEF', $owner);
        $circle2 = TestEntityFactory::makeCircle(20, 'Member Circle', '#FEDCBA', $owner, [$member]);

        $this->circleRepository->setCircles([$circle1, $circle2]);

        $authUser = new class($member->getId()) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $result = $this->service->get($authUser);

        $this->assertCount(1, $result);
        $this->assertEquals('Member Circle', $result[0]->name);
    }

    public function testReturnsEmptyArrayWhenNoCircles(): void
    {
        $this->circleRepository->setCircles([]);

        $authUser = new class(1) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $result = $this->service->get($authUser);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}