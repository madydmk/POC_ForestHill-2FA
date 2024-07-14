<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwoFactorAuthService;
use Doctrine\ORM\EntityManagerInterface;

class EspaceProController extends AbstractController
{
    private $twoFactorAuthService;

    public function __construct(TwoFactorAuthService $twoFactorAuthService)
    {
        $this->twoFactorAuthService = $twoFactorAuthService;
    }
    
    #[Route('/espace-pro', name: 'espace-pro')]
    public function espacePro()
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->redirectToRoute('login');
        }else if (!$this->twoFactorAuthService->isTwoFactorAuthenticated()){
            return $this->redirectToRoute('2fa');
        }

        return $this->render('pro/espace-pro.html.twig', [
            'user' => $user,
            'controller_name' => 'EspaceProController',
        ]);
    }
}