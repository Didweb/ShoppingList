<?php
namespace App\Service\Circle;

use App\Entity\User;
use App\Repository\CircleRepository;

class CircleAccessService
{
    public function __construct(private CircleRepository $circleRepository)
    {}

    public function getAllowedOwnerIds(int $idUser): array
    {
        $circles = $this->circleRepository->findByOwnerOrMembership($idUser);

        $ownerIds = [];
        foreach ($circles as $circle) {
            $ownerIds[] = $circle->getCreatedBy()->getId();
        }

        return array_unique($ownerIds);
    }
}