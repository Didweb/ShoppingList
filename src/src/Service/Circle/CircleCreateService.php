<?php
namespace App\Service\Circle;

use App\Entity\User;
use App\Entity\Circle;
use App\ValueObject\HexColor;
use App\DTO\Circle\CircleCreateDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;

class CircleCreateService
{
    public function __construct(
            private CircleRepository $cricleRepository,
            private EntityManagerInterface $em,
            private CircleQrService $qrService)
    {}

    public function create(User $user, CircleCreateDto $cirleCreateDto): void
    {
        $circle = new Circle();
        $circle->setName($cirleCreateDto->name);
        $circle->setColor(new HexColor($cirleCreateDto->color));
        $circle->setCreatedBy($user);

        $this->em->persist($circle);
        $this->em->flush();

        $payload = $this->qrService->generatePayload($circle->getId());

        $this->qrService->generateAndSaveQrImage($circle->getId());

        $circle->setQr($payload);

        $this->em->persist($circle);
        $this->em->flush();
        
    }
}