<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class RootRedirectController extends AbstractController
{
    #[Route('/', name: 'app_root_redirect')]
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('app_politics');
    }
}