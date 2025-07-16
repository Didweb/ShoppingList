<?php
namespace App\Tests\Service\Circle;

use App\Entity\User;
use App\Entity\Circle;
use App\ValueObject\HexColor;
use App\DTO\Circle\CircleGetDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\Exception\UnauthorizedException;
use App\Service\Circle\CircleGetService;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Utils\CircleAccessCheckerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CircleGetServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private CircleRepository $circleRepository;
    private CircleQrService $qrService;
    private CircleAccessCheckerInterface $accessChecker;
    private CircleGetService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->circleRepository = $container->get(CircleRepository::class);
        $this->qrService = $container->get(CircleQrService::class);

        // Usamos un checker falso para poder controlar acceso
        $this->accessChecker = new class implements CircleAccessCheckerInterface {
            private bool $access = true;

            public function setAccess(bool $access): void {
                $this->access = $access;
            }

            public function userCanAccessCircle($circle, $user): bool {
                return $this->access;
            }
        };


        $this->service = new CircleGetService(
            $this->circleRepository,
            $this->em,
            $this->qrService,
            $this->accessChecker
        );

        $this->purgeData();
    }

    private function purgeData(): void
    {
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testGetReturnsCircleDtoSuccessfully(): void
    {
        $user = new User();
        $user->setEmail('realget@example.com');
        $user->setName('Tester');
        $user->setPassword('pass');
        $this->em->persist($user);

        $circle = new Circle();
        $circle->setName('Circle Test');
        $circle->setColor(new HexColor('#ABCDEF'));
        $circle->setCreatedBy($user);

        $this->em->persist($circle);
        $this->em->flush();

        $dto = new CircleGetDto($circle->getId());

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $result = $this->service->get($dto, $authUser);

        $this->assertSame($circle->getId(), $result->id);
        $this->assertNotEmpty($result->imageQrBase64);
    }

    public function testThrowsEntityNotFoundExceptionIfCircleNotFound(): void
    {
        $dto = new CircleGetDto(9999);

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $this->expectException(EntityNotFoundException::class);

        $this->service->get($dto, $authUser);
    }

    public function testThrowsUnauthorizedExceptionIfUserCannotAccess(): void
    {
        $user = new User();
        $user->setEmail('unauth@example.com');
        $user->setName('NoAccess');
        $user->setPassword('pass');
        $this->em->persist($user);

        $circle = new Circle();
        $circle->setName('Private');
        $circle->setColor(new HexColor('#000000'));
        $circle->setCreatedBy($user);
        $this->em->persist($circle);
        $this->em->flush();

        $this->createDummyQrPng($circle->getId());

        $authUser = $this->createMock(AuthenticatedUserInterface::class);
        $this->accessChecker->userCanAccessCircle($circle, $authUser);

        $dto = new CircleGetDto($circle->getId());

        $this->expectException(UnauthorizedException::class);

        $this->service->get($dto, $authUser);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->purgeData();
        $this->em->close();
    }

    private function createDummyQrPng(int $circleId): void
    {
         $qrDir = '/var/www/html/public/qr_codes';
    if (!is_dir($qrDir)) {
        if (!mkdir($qrDir, 0777, true) && !is_dir($qrDir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $qrDir));
        }
    }
    $pngPath = $qrDir . '/qr_' . $circleId . '.png';

    if (!file_exists($pngPath)) {
        $img = imagecreatetruecolor(100, 100);
        if ($img === false) {
            throw new \RuntimeException('No se pudo crear la imagen GD');
        }

        $bgColor = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $bgColor);

        $saved = imagepng($img, $pngPath);
        imagedestroy($img);

        if (!$saved) {
            throw new \RuntimeException("No se pudo guardar el archivo PNG en: $pngPath");
        }
    }

    if (!file_exists($pngPath)) {
        throw new \RuntimeException("Archivo PNG no existe después de crear: $pngPath");
    }
    }
    
}