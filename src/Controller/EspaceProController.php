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
    public function espace_pro()
    {
        $user = $this->getUser();
        
        if (!$user) {
            throw new AccessDeniedException('User not authenticated.');
        }
        
        return $this->render('pro/espace-pro.html.twig');
    }
}