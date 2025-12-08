<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PoliticsController  extends AbstractController
{
    #[Route('/politics', name: 'app_politics')]
    public function index(): Response
    {
        return $this->render('politics/index.html.twig', [
            'title' => 'Política de privacidad',
        ]);
    }
}