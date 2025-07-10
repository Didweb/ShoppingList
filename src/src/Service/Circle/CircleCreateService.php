<?php
namespace App\Service\Circle;

use App\DTO\Circle\CircleCreateDto;
use App\Entity\Circle;
use App\ValueObject\HexColor;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CircleCreateService
{
    public function __construct(
            private CircleRepository $cricleRepository,
            private EntityManagerInterface $em,
            private CircleQrService $qrService)
    {}

    public function create(?CircleCreateDto $cirleCreateDto, bool $isDefault = false ): void
    {
        // $user = $this->security->getUser();

        // if (!$user) {
        //     throw new \RuntimeException('Usuario no encontrado');
        // }
       
        // $circle = new Circle();
        // $circle->setName('Principal');
        // $circle->setColor(new HexColor('#7233A0'));
        // $circle->setCreatedBy($user);

        // $this->em->persist($circle);
        // $this->em->flush();

        // $payload = $this->qrService->generatePayload($circle->getId());

        // $this->qrService->generateAndSaveQrImage($circle->getId());

        // $circle->setQr($payload);

        // $this->em->persist($circle);
        // $this->em->flush();
        
    }
}