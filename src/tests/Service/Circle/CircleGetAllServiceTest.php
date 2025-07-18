<?php
namespace App\Tests\Service\Circle;

use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\Tests\Factory\TestEntityFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Service\Circle\CircleGetAllService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CircleGetAllServiceTest extends KernelTestCase
{
    private CircleRepository $circleRepository;
    private EntityManagerInterface $em;
    private CircleGetAllService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->circleRepository = $container->get(CircleRepository::class);

        $this->service = new CircleGetAllService(
            $this->circleRepository,
            $this->em,
            $container->get(CircleQrService::class)
        );

        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();

    }

    public function testReturnsAllOwnedCircles(): void
    {
        $owner = TestEntityFactory::makeUser(1, 'name');
        $this->em->persist($owner);

        $circle1 = TestEntityFactory::makeCircle(10, 'Circle 1', '#123456', $owner);
        $circle2 = TestEntityFactory::makeCircle(20, 'Circle 2', '#654321', $owner);

        $this->em->persist($circle1);
        $this->em->persist($circle2);

        $this->em->flush();

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
        $owner = TestEntityFactory::makeUser(1,'owner', 'info@owner.com');
        $member = TestEntityFactory::makeUser(2,'member', 'info@member.com');

        $this->em->persist($owner);
        $this->em->persist($member);

        $circle1 = TestEntityFactory::makeCircle(10, 'Owned Circle', '#ABCDEF', $owner);
        $circle2 = TestEntityFactory::makeCircle(20, 'Member Circle', '#FEDCBA', $owner, [$member]);

        $this->em->persist($circle1);
        $this->em->persist($circle2);

        $this->em->flush();

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
        
        $authUser = new class(1) implements AuthenticatedUserInterface {
            public function __construct(private int $id) {}
            public function getId(): int { return $this->id; }
        };

        $result = $this->service->get($authUser);

        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }
}