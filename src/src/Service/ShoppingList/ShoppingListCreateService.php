<?php
namespace App\Service\ShoppingList;

use App\Entity\User;
use App\Entity\ShoppingList;
use App\Repository\CircleRepository;
use App\Exception\UnauthorizedException;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\AuthenticatedUserInterface;
use App\Exception\EntityNotFoundException;
use App\Utils\CircleAccessCheckerInterface;
use App\DTO\ShoppingList\ShoppingListCreateDto;

class ShoppingListCreateService
{
    public function __construct(
            private CircleRepository $cricleRepository,
            private EntityManagerInterface $em,
            private CircleAccessCheckerInterface $accessChecker)
    {}

    public function create(User $user, ShoppingListCreateDto $shoppingListCreateDto, AuthenticatedUserInterface $authUser): void
    {

        $circle = $this->cricleRepository->find($shoppingListCreateDto->idCircle);

        if(!$circle) {
            throw new EntityNotFoundException('Circle con id: ['.$shoppingListCreateDto->idCircle.'] no encontrado.');
        }

        if (!$this->accessChecker->userCanAccessCircle($circle, $authUser)) {
            throw new UnauthorizedException('No tienes acceso a este círculo.');
        }

        $shoppingList = new ShoppingList();
        $shoppingList->setName($shoppingListCreateDto->name);
        $shoppingList->setCreatedBy($user);
        $shoppingList->setCreateAt(new \DateTimeImmutable('now'));
        $shoppingList->setCircle($circle);

        $this->em->persist($shoppingList);
        $this->em->flush();
        
    }
}