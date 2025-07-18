<?php
namespace App\Tests\Service\Circle;

use App\Entity\User;
use App\Entity\Circle;
use PHPUnit\Framework\TestCase;
use App\Repository\CircleRepository;
use App\Service\Circle\CircleAccessService;

class CircleAccessServiceTest extends TestCase
{
    private CircleRepository $circleRepository;
    private CircleAccessService $service;

    protected function setUp(): void
    {
        $this->circleRepository = $this->createMock(CircleRepository::class);
        $this->service = new CircleAccessService($this->circleRepository);
    }

    public function testReturnsEmptyArrayWhenNoCircles(): void
    {
        $this->circleRepository
            ->method('findByOwnerOrMembership')
            ->with(42)
            ->willReturn([]);

        $result = $this->service->getAllowedOwnerIds(42);
        $this->assertSame([], $result);
    }

    public function testReturnsOwnerIdsForSingleCircle(): void
    {
        $user = $this->createConfiguredMock(User::class, [
            'getId' => 7,
        ]);

        $circle = $this->createConfiguredMock(Circle::class, [
            'getCreatedBy' => $user,
        ]);

        $this->circleRepository
            ->method('findByOwnerOrMembership')
            ->with(99)
            ->willReturn([$circle]);

        $result = $this->service->getAllowedOwnerIds(99);

        $this->assertSame([7], $result);
    }

    public function testReturnsUniqueOwnerIds(): void
    {
        $owner1 = $this->createConfiguredMock(User::class, ['getId' => 1]);
        $owner2 = $this->createConfiguredMock(User::class, ['getId' => 2]);
        $ownerDuplicate = $this->createConfiguredMock(User::class, ['getId' => 1]);

        $circle1 = $this->createConfiguredMock(Circle::class, ['getCreatedBy' => $owner1]);
        $circle2 = $this->createConfiguredMock(Circle::class, ['getCreatedBy' => $owner2]);
        $circle3 = $this->createConfiguredMock(Circle::class, ['getCreatedBy' => $ownerDuplicate]);

        $this->circleRepository
            ->method('findByOwnerOrMembership')
            ->with(10)
            ->willReturn([$circle1, $circle2, $circle3]);

        $result = $this->service->getAllowedOwnerIds(10);

        $this->assertCount(2, $result);
        $this->assertContains(1, $result);
        $this->assertContains(2, $result);
    }
}