<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SafetyController  extends AbstractController
{
    #[Route('/safety', name: 'app_safety')]
    public function index(): Response
    {
        return $this->render('safety.html.twig', [
            'title' => 'Política de privacidad',
        ]);
    }
}