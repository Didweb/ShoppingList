<?php
namespace App\Controller;

use App\Utils\JsonResponseFactory;
use App\Service\Auth\DeleteCountService;
use App\Utils\AuthenticatedUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/delete-count', name: 'api_delete_count', methods: ['DELETE'])]
final class  DeleteUserController extends AbstractController
{
    public function __construct(
        private DeleteCountService $deleteCountService,
        private AuthenticatedUserInterface $authUser
    ) {}

    public function __invoke(Request $request): JsonResponse
    {

        $user = $this->getUser();
        
        $this->deleteCountService->deleteAccount($user->getId());

        return JsonResponseFactory::success([
            'message' => 'El Usuario ha sido eliminado del sistema'], 
            Response::HTTP_OK);
    }
}