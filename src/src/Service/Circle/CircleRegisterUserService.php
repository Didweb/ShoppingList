<?php
namespace App\Service\Circle;

use App\DTO\Circle\CircleDto;
use App\Repository\UserRepository;
use App\Service\Qr\CircleQrService;
use App\DTO\Circle\CirclePayloadDto;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\Circle\CircleRegisterUserDto;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;

class CircleRegisterUserService
{
    public function __construct(
            private CircleRepository $cricleRepository,
            private UserRepository $userRepository,
            private EntityManagerInterface $em,
            private CircleQrService $qrService)
    {}

    public function register(CirclePayloadDto $circlePayloadDto, AuthenticatedUserInterface $authUser): CircleDto
    {
        $circle = $this->cricleRepository->find($circlePayloadDto->id); 

        if(!$circle) {
            throw new EntityNotFoundException('Circle con id: ['.$circlePayloadDto->id.'] no encontrado.');
        }

        $registerUser = $this->userRepository->find($authUser->getId()); 

        if(!$registerUser) {
            throw new EntityNotFoundException('User con id: ['.$authUser->getId().'] no encontrado.');
        }

        $circle->addUser($registerUser);

        $this->em->persist($circle);
        $this->em->flush();

        $imageBase64 = $this->qrService->getQrImageBase64FromFile($circlePayloadDto->id);

        return CircleDto::fromEntity($circle, $imageBase64);
    }
}