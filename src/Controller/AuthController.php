<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Service\TwoFactorAuthService;

class AuthController extends AbstractController
{
    private $twoFactorAuthService;

    public function __construct(TwoFactorAuthService $twoFactorAuthService)
    {
        $this->twoFactorAuthService = $twoFactorAuthService;
    }

    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isMethod('POST')) {
            return $this->redirectToRoute('2fa');
        }

        if ($this->getUser()) {
            if($this->twoFactorAuthService->isTwoFactorAuthenticated()){
                return $this->redirectToRoute('espace-pro');
            }else{
                return $this->redirectToRoute('2fa');
            }
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    
    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        
    }

    #[Route(path: '/logout_callback', name: 'logout_callback')]
    public function logoutCallback()
    {
        $user = $this->getUser();

        if ($user) {
            $this->twoFactorAuthService->setTwoFactorAuthenticated(false);
        }

        return $this->redirectToRoute('login');
    }
}
