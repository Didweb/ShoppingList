<?php
namespace App\Service\Circle;

use App\DTO\Circle\CircleDto;
use App\DTO\Circle\CircleGetDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Utils\CircleAccessCheckerInterface;

class CircleGetService
{
    public function __construct(
            private CircleRepository $circleRepository,
            private EntityManagerInterface $em,
            private CircleQrService $qrService,
            private CircleAccessCheckerInterface $accessChecker)
    {}

    public function get(CircleGetDto $circleGetDto, AuthenticatedUserInterface $authUser): CircleDto
    {
        $circle = $this->circleRepository->find($circleGetDto->id);

        if(!$circle) {
            throw new EntityNotFoundException('Circle con id: ['.$circleGetDto->id.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessCircle($circle, $authUser)) {
            throw new UnauthorizedException('No tienes acceso a este círculo.');
        }

        $imageBase64 = $this->qrService->getQrImageBase64FromFile($circleGetDto->id);

        return  CircleDto::fromEntity($circle, $imageBase64);
    }
}