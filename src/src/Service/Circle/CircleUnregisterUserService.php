<?php
namespace App\Service\Circle;

use App\Repository\UserRepository;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use App\DTO\Circle\CircleUnregisterDto;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;

class CircleUnregisterUserService
{
    public function __construct(
            private CircleRepository $cricleRepository,
            private UserRepository $userRepository,
            private EntityManagerInterface $em,
            private CircleQrService $qrService)
    {}

    public function unregister(CircleUnregisterDto $circleUnregisterDto, AuthenticatedUserInterface $authUser): void
    {
        $circle = $this->cricleRepository->find($circleUnregisterDto->id); 

        if(!$circle) {
            throw new EntityNotFoundException('Circle con id: ['.$circleUnregisterDto->id.'] no encontrado.');
        }

        $registerUser = $this->userRepository->find($authUser->getId()); 

        if(!$registerUser) {
            throw new EntityNotFoundException('User con id: ['.$authUser->getId().'] no encontrado.');
        }

        $circle->removeUser($registerUser);

        $this->em->persist($circle);
        $this->em->flush();
        
    }
}