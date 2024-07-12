<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwoFactorAuthService;
use Doctrine\ORM\EntityManagerInterface;

class EspaceProController extends AbstractController
{
    #[Route('/espace_pro', name: 'espace_pro')]
    public function index(): Response
    {
        return $this->render('pro/espace-pro.html.twig', [
            'controller_name' => 'EspaceProController',
        ]);
    }
}