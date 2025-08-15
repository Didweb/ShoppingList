<?php
namespace App\Service\Circle;

use App\DTO\Circle\CircleDeleteDto;
use App\Repository\CircleRepository;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Utils\CircleAccessCheckerInterface;

class CircleDeleteService
{
    public function __construct(
        private CircleRepository $circleRepository,
        private EntityManagerInterface $em,
        private CircleAccessCheckerInterface $accessChecker
    )
    {} 

    public function delete(CircleDeleteDto $circleDeleteDto, AuthenticatedUserInterface $authUser): void
    {
        $circle = $this->circleRepository->find($circleDeleteDto->id);

        if(!$circle) {
            throw new EntityNotFoundException('Circle con id: ['.$circleDeleteDto->id.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessCircle($circle, $authUser)) {
            throw new UnauthorizedException('No tienes acceso a este círculo.');
        }

        $this->em->remove($circle);
        $this->em->flush();
        
    }
}