<?php
namespace App\Service\Circle;

use App\DTO\Circle\CircleDto;
use App\DTO\Circle\CircleEditDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Utils\CircleAccessCheckerInterface;
use App\ValueObject\HexColor;

class CircleEditService
{
    public function __construct(
        private CircleRepository $cricleRepository,
        private EntityManagerInterface $em,
        private CircleQrService $qrService,
        private CircleAccessCheckerInterface $accessChecker
        )
    {}

     public function edit(CircleEditDto $circleEditDto, AuthenticatedUserInterface $authUser): CircleDto
     {
        $circle = $this->cricleRepository->find($circleEditDto->id);

        if(!$circle) {
            throw new EntityNotFoundException('Circle con id: ['.$circleEditDto->id.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessCircle($circle, $authUser)) {
            throw new UnauthorizedException('No tienes acceso a este círculo.');
        }

        if ($circleEditDto->name) {
            $circle->setName($circleEditDto->name);
        }


        if ($circleEditDto->color) {
            $circle->setColor(new HexColor($circleEditDto->color));
        }

        $this->em->persist($circle);
        $this->em->flush();

        $imageBase64 = $this->qrService->getQrImageBase64FromFile($circleEditDto->id);

        return CircleDto::fromEntity($circle, $imageBase64);
     }
}