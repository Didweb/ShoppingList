<?php
namespace App\Service\Circle;

use App\Entity\User;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;

class CircleDeleteByOwnerService
{
    public function __construct(
        private CircleRepository $circleRepository,
        private EntityManagerInterface $em
    ) {}

    public function deleteByOwner(User $user): void
    {
        $circles = $this->circleRepository->findBy(['createdBy' => $user]);

        foreach ($circles as $circle) {
            $this->em->remove($circle);
        }
    }
}