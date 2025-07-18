<?php
namespace App\Tests\Service\Circle;

use App\Entity\User;
use App\Entity\Circle;
use App\Repository\UserRepository;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\DTO\Circle\CircleUnregisterDto;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Service\Circle\CircleUnregisterUserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CircleUnregisterUserServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private CircleRepository $circleRepository;
    private UserRepository $userRepository;
    private CircleQrService $qrService; // No se usa pero el servicio lo requiere
    private CircleUnregisterUserService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->circleRepository = $container->get(CircleRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->qrService = $container->get(CircleQrService::class);

        $this->service = new CircleUnregisterUserService(
            $this->circleRepository,
            $this->userRepository,
            $this->em,
            $this->qrService
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testUnregisterRemovesUserFromCircleSuccessfully(): void
    {
        // Arrange: Creamos User y Circle
        $user = new User();
        $user->setEmail('unregister@example.com');
        $user->setName('UnregisterUser');
        $user->setPassword('pass');
        $this->em->persist($user);

        $circle = new Circle();
        $circle->setName('Circle Unreg');
        $circle->setColor(new \App\ValueObject\HexColor('#abcdef'));
        $circle->setCreatedBy($user);
        $circle->addUser($user);
        $this->em->persist($circle);

        $this->em->flush();

        // Aseguramos que el usuario está registrado
        $this->assertTrue($circle->getUsers()->contains($user));

        // Mock de AuthenticatedUserInterface
        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $authUser->method('getId')->willReturn($user->getId());

        $dto = new CircleUnregisterDto($circle->getId());

        // Act
        $this->service->unregister($dto, $authUser);

        // Clear y reload para verificar del lado DB
        $this->em->clear();
        $updatedCircle = $this->circleRepository->find($circle->getId());
        $updatedUser = $this->userRepository->find($user->getId());

        // Assert
        $this->assertFalse($updatedCircle->getUsers()->contains($updatedUser));
    }

    public function testThrowsEntityNotFoundExceptionWhenCircleDoesNotExist(): void
    {
        $dto = new CircleUnregisterDto(9999);

        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $authUser->method('getId')->willReturn(1);

        $this->expectException(EntityNotFoundException::class);
        $this->service->unregister($dto, $authUser);
    }

    public function testThrowsEntityNotFoundExceptionWhenUserDoesNotExist(): void
    {
        // Creamos Circle
        $creator = new User();
        $creator->setEmail('creator@example.com');
        $creator->setName('Creator');
        $creator->setPassword('pass');
        $this->em->persist($creator);

        $circle = new Circle();
        $circle->setName('Circle NoUser');
        $circle->setColor(new \App\ValueObject\HexColor('#999999'));
        $circle->setCreatedBy($creator);
        $this->em->persist($circle);

        $this->em->flush();

        $dto = new CircleUnregisterDto($circle->getId());

        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $authUser->method('getId')->willReturn(999999);

        $this->expectException(EntityNotFoundException::class);
        $this->service->unregister($dto, $authUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->purgeData();
        $this->em->close();
    }
}