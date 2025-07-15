<?php
namespace App\Tests\Service;

use App\Entity\User;
use App\Entity\Circle;
use App\ValueObject\HexColor;
use PHPUnit\Framework\TestCase;
use App\DTO\Circle\CircleCreateDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Circle\CircleCreateService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CircleCreateServiceTest extends KernelTestCase
{
    private EntityManagerInterface $em;
    private CircleRepository $circleRepository;
    private CircleCreateService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->em = $container->get(EntityManagerInterface::class);
        $this->circleRepository = $container->get(CircleRepository::class);

        // Usamos el servicio real, sin mocks.
        $this->service = new CircleCreateService(
            $this->circleRepository,
            $this->em,
            $container->get(CircleQrService::class)
        );

        // Limpiamos para evitar datos sucios
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
    }

    public function testCreatePersistsCircleWithValidData(): void
    {
        // Creamos User real
        $user = new User();
        $user->setEmail('real@example.com');
        $user->setName('Juan');
        $user->setPassword('password');
        $this->em->persist($user);
        $this->em->flush();

        // DTO válido
        $dto = new CircleCreateDto('Circle Real Test', '#00FF00');

        $this->service->create($user, $dto);

        // Verificamos que se creó 1 Circle
        $circles = $this->circleRepository->findAll();
        $this->assertCount(1, $circles);

        $circle = $circles[0];
        $this->assertSame('Circle Real Test', $circle->getName());
        $this->assertSame('#00FF00', $circle->getColor()->value());
        $this->assertSame($user->getId(), $circle->getCreatedBy()->getId());

        $this->assertNotNull($circle->getQr(), 'QR no generado correctamente.');
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        $this->em->createQuery('DELETE FROM App\Entity\Circle')->execute();
        $this->em->createQuery('DELETE FROM App\Entity\User')->execute();
        $this->em->close();
    }
}