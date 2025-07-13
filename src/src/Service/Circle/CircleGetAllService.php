<?php
namespace App\Service\Circle;

use App\DTO\Circle\CircleDto;
use App\DTO\Circle\CircleSimpleDto;
use App\Service\Qr\CircleQrService;
use App\Repository\CircleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\DTO\ShoppingList\ShoppingListDto;
use App\Utils\AuthenticatedUserInterface;
use App\DTO\ShoppingListItem\ShoppingListItemDto;

class CircleGetAllService
{
    public function __construct(
        private CircleRepository $cricleRepository,
        private EntityManagerInterface $em,
        private CircleQrService $qrService)
    {}

    public function get(AuthenticatedUserInterface $authUser): array
    {

        $circles = $this->cricleRepository->findByOwnerOrMembership($authUser->getId());

        $circlesArr = [];

        foreach ($circles as $circle) {
            
            $circlesArr[] = CircleSimpleDto::fromEntity($circle);
        }

        return $circlesArr;

    }
}