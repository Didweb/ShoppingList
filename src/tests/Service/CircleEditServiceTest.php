<?php
namespace App\Tests\Service;

use App\Entity\Circle;
use App\DTO\Circle\CircleDto;
use App\ValueObject\HexColor;
use PHPUnit\Framework\TestCase;
use App\DTO\Circle\CircleEditDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Circle\CircleEditService;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Tests\Factory\TestEntityFactory;
use App\Utils\CircleAccessCheckerInterface;

class CircleEditServiceTest extends TestCase
{
    private CircleRepository $circleRepository;
    private EntityManagerInterface $entityManager;
    private CircleQrService $qrService;
    private CircleAccessCheckerInterface $accessChecker;
    private CircleEditService $service;

    protected function setUp(): void
    {
        // Aquí deberías inicializar repositorio y entityManager con la base real o test doubles.
        // Para este ejemplo, supongamos que mocks para qrService y accessChecker, reales para repo y em

        $this->qrService = $this->createMock(CircleQrService::class);
        $this->accessChecker = $this->createMock(CircleAccessCheckerInterface::class);

        // Supón que los otros dos los obtienes del contenedor o creas stubs funcionales:
        // $this->circleRepository = ... (ejemplo: repositorio real, o mocked si no tienes base real)
        // $this->entityManager = ... (idem)

        // Pero aquí creamos mocks para simplificar la demo
        $this->circleRepository = $this->createMock(CircleRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->service = new CircleEditService(
            $this->circleRepository,
            $this->entityManager,
            $this->qrService,
            $this->accessChecker
        );
    }

    public function testEditThrowsEntityNotFoundExceptionIfCircleNotFound(): void
    {
        $dto = new CircleEditDto(999, null, null, 2);

        $this->circleRepository->expects($this->once())
            ->method('find')
            ->with(999)
            ->willReturn(null);

        $this->expectException(EntityNotFoundException::class);
        $this->expectExceptionMessage('Circle con id: [999] no encontrado.');

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $this->service->edit($dto, $authUser);
    }

    public function testEditThrowsUnauthorizedExceptionIfUserHasNoAccess(): void
    {
        $user = TestEntityFactory::makeUser(8, 'Test User 8');
        
        $circle = TestEntityFactory::makeCircle(
                        122,
                        'Old Name',
                        '#FFFFFF',
                        $user
                    );

        $dto = new CircleEditDto(1, 'Nuevo Nombre', '#000000', 2);


        $this->circleRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($circle);

        $this->accessChecker->expects($this->once())
            ->method('userCanAccessCircle')
            ->with($circle, $this->anything())
            ->willReturn(false);

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('No tienes acceso a este círculo.');

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $this->service->edit($dto, $authUser);
    }

    public function testEditUpdatesNameAndColorAndReturnsDto(): void
    {
        $user = TestEntityFactory::makeUser(7, 'Test User 7');
        
        $circle = TestEntityFactory::makeCircle(
                        123,
                        'Old Name',
                        '#FFFFFF',
                        $user
                    );


        $dto = new CircleEditDto(123, 'New Name', '#00FF00', 2);

        $this->circleRepository->expects($this->once())
            ->method('find')
            ->with(123)
            ->willReturn($circle);

        $this->accessChecker->expects($this->once())
            ->method('userCanAccessCircle')
            ->with($circle, $this->anything())
            ->willReturn(true);

        $this->entityManager->expects($this->exactly(1))
            ->method('persist')
            ->with($circle);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->qrService->expects($this->once())
            ->method('getQrImageBase64FromFile')
            ->with(123)
            ->willReturn('base64string');

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $resultDto = $this->service->edit($dto, $authUser);

        $this->assertInstanceOf(CircleDto::class, $resultDto);
        $this->assertSame('New Name', $circle->getName());
        $this->assertEquals(new HexColor('#00FF00'), $circle->getColor());
        $this->assertSame('base64string', $resultDto->imageQrBase64);
    }

    public function testEditUpdatesOnlyNameIfColorNull(): void
    {
        $user = TestEntityFactory::makeUser(5, 'Test User 5');
        
        $circle = TestEntityFactory::makeCircle(
                        124,
                        'Old Name',
                        '#FFFFFF',
                        $user
                    );

        $dto = new CircleEditDto(124, 'New Name', null, 2);

        $this->circleRepository->expects($this->once())
            ->method('find')
            ->with(124)
            ->willReturn($circle);

        $this->accessChecker->expects($this->once())
            ->method('userCanAccessCircle')
            ->with($circle, $this->anything())
            ->willReturn(true);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($circle);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->qrService->expects($this->once())
            ->method('getQrImageBase64FromFile')
            ->with(124)
            ->willReturn('base64string2');

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $resultDto = $this->service->edit($dto, $authUser);

        $this->assertSame('New Name', $circle->getName());
        $this->assertEquals(new HexColor('#FFFFFF'), $circle->getColor());
        $this->assertSame('base64string2', $resultDto->imageQrBase64);
    }

    public function testEditUpdatesOnlyColorIfNameNull(): void
    {

        $user = TestEntityFactory::makeUser(2, 'Test User');

        $circle = TestEntityFactory::makeCircle(
                        125,
                        'Old Name',
                        '#FFFFFF',
                        $user
                    );

        $dto = new CircleEditDto(125, null, '#ABCDEF', 2);

        $this->circleRepository->expects($this->once())
            ->method('find')
            ->with(125)
            ->willReturn($circle);

        $this->accessChecker->expects($this->once())
            ->method('userCanAccessCircle')
            ->with($circle, $this->anything())
            ->willReturn(true);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($circle);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $this->qrService->expects($this->once())
            ->method('getQrImageBase64FromFile')
            ->with(125)
            ->willReturn('base64string3');

        $authUser = $this->createMock(AuthenticatedUserInterface::class);

        $resultDto = $this->service->edit($dto, $authUser);

        $this->assertSame('Old Name', $circle->getName());
        $this->assertEquals(new HexColor('#ABCDEF'), $circle->getColor());
        $this->assertSame('base64string3', $resultDto->imageQrBase64);
    }
}