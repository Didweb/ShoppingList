<?php
namespace App\Tests\Service\Circle;

use App\Entity\User;
use App\Entity\Circle;
use App\DTO\Circle\CircleDto;
use App\ValueObject\HexColor;
use App\Repository\UserRepository;
use App\Service\Qr\CircleQrService;
use App\DTO\Circle\CirclePayloadDto;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Service\Circle\CircleRegisterUserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CircleRegisterUserServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private CircleRepository $circleRepository;
    private UserRepository $userRepository;
    private CircleQrService $qrService;
    private CircleRegisterUserService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->circleRepository = $container->get(CircleRepository::class);
        $this->userRepository = $container->get(UserRepository::class);
        $this->qrService = $container->get(CircleQrService::class);

        $this->service = new CircleRegisterUserService(
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

    public function testRegisterAddsUserToCircleSuccessfully(): void
    {
        // Creamos User y Circle
        $user = new User();
        $user->setEmail('register@example.com');
        $user->setName('RegisterUser');
        $user->setPassword('pass');
        $this->em->persist($user);

        $circle = new Circle();
        $circle->setName('Circle Reg');
        $circle->setColor(new HexColor('#123456'));
        $circle->setCreatedBy($user);
        $this->em->persist($circle);

        $this->em->flush();

        // Dummy QR PNG para simular base64 correcto
        $this->createDummyQrPng($circle->getId());

        // Mock de AuthenticatedUserInterface
        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $authUser->method('getId')->willReturn($user->getId());

        $payload = new CirclePayloadDto(
                            type: 'circle',
                            id: $circle->getId(),
                            v: 1);

        $result = $this->service->register($payload, $authUser);

        $this->assertInstanceOf(CircleDto::class, $result);
        $this->assertSame($circle->getId(), $result->id);
        $this->assertNotEmpty($result->imageQrBase64);

        // Verifica relación persistida
        $this->assertTrue($circle->getUsers()->contains($user));
    }

    public function testThrowsEntityNotFoundExceptionWhenCircleDoesNotExist(): void
    {
        $payload = new CirclePayloadDto(
                            type: 'circle',
                            id: 9999,
                            v: 1);

        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $authUser->method('getId')->willReturn(1);

        $this->expectException(EntityNotFoundException::class);
        $this->service->register($payload, $authUser);
    }

    public function testThrowsEntityNotFoundExceptionWhenUserDoesNotExist(): void
    {
        // Creamos solo Circle
        $user = new User();
        $user->setEmail('creator@example.com');
        $user->setName('Creator');
        $user->setPassword('pass');
        $this->em->persist($user);

        $circle = new Circle();
        $circle->setName('Circle NoUser');
        $circle->setColor(new HexColor('#999999'));
        $circle->setCreatedBy($user);
        $this->em->persist($circle);

        $this->em->flush();

        $this->createDummyQrPng($circle->getId());

        // AuthUser apunta a un ID inexistente
        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $authUser->method('getId')->willReturn(123456);

        $payload = new CirclePayloadDto(
                            type: 'circle',
                            id: $circle->getId(),
                            v: 1);

        $this->expectException(EntityNotFoundException::class);

        $this->service->register($payload, $authUser);
    }

    private function createDummyQrPng(int $circleId): void
    {
        $qrDir = '/var/www/html/public/qr_codes';
        if (!is_dir($qrDir)) {
            mkdir($qrDir, 0777, true);
        }

        $pngPath = $qrDir . '/qr_' . $circleId . '.png';

        if (!file_exists($pngPath)) {
            $img = imagecreatetruecolor(100, 100);
            $bg = imagecolorallocate($img, 255, 255, 255);
            imagefill($img, 0, 0, $bg);
            imagepng($img, $pngPath);
            imagedestroy($img);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->purgeData();
        $this->em->close();
    }
}