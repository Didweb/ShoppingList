<?php
namespace App\Service\Item;

use App\Entity\Item;
use App\DTO\Item\ItemDto;
use App\ValueObject\Slug;
use App\DTO\Item\ItemAddDto;
use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Circle\CircleAccessService;

class ItemCreateService
{
    public function __construct(
        private ItemRepository $itemRepository,
        private UserRepository $userRepository,
        private CircleAccessService $circleAccessService,
        private EntityManagerInterface $em) 
    {}

    public function createOrGet(ItemAddDto $itemAddDto): ItemDto
    {
        if ($itemAddDto->idSelectedItem) {

            $existing = $this->itemRepository->find($itemAddDto->idSelectedItem);

            if (!$existing) {
                throw new \Exception('Item sugerido no encontrado');
            }
       
            return ItemDto::fromEntity($existing);
        }

        $slug = (new Slug(trim(mb_strtolower($itemAddDto->name))))->value();

        $allowedOwnerIds = $this->circleAccessService->getAllowedOwnerIds($itemAddDto->idUser);
        $existing = $this->itemRepository->findOneBySlugAndOwners($slug, $allowedOwnerIds);

        if ($existing) {
            return ItemDto::fromEntity($existing);
        }

        $newItem = new Item();
        $newItem->setName($itemAddDto->name);
        $newItem->setSlug(new Slug($itemAddDto->name));

        $user = $this->userRepository->find($itemAddDto->idUser);
        $newItem->setCreatedBy($user);

        $this->em->persist($newItem);
        $this->em->flush();

        return ItemDto::fromEntity($newItem);
    }
}