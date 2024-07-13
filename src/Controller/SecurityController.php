<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    // #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    // public function login(AuthenticationUtils $authenticationUtils, Request $request)
    // {
    //     $error = $authenticationUtils->getLastAuthenticationError();

    //     $lastUsername = $authenticationUtils->getLastUsername();
        
    //     if ($request->isMethod('POST')) {
    //         return $this->redirectToRoute('2fa');
    //     }

    //     return $this->render('security/login.html.twig', [
    //         'last_username' => $lastUsername,
    //         'error'         => $error,
    //     ]);
    // }
    
    /**
     * @Route("/logout", name="logout")
     */
    // #[Route('/logout', name: 'logout')]
    // public function logout(Request $request)
    // {
    //     $session = $request->getSession();
    //     throw new \Exception('This should never be reached!');
    // }
}
?>