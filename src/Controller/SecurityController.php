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
    #[Route('/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();
        
        if ($request->isMethod('POST')) {
            $this->render('security/2fa.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
        }


        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    #[Route('/logout', name: 'logout')]
    public function logout(Request $request)
    {
        $session = $request->getSession();
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/2fa", name="2fa_login")
     */
    #[Route("/2fa", name:"2fa_login")]
    public function check2fa(Request $request)
    {
        if ($request->isMethod('POST')) { // Et on vient de login
            //recup code de google auth
            //comparer avec le code saisi
            //if bon, redirection vers espace pro
            //sinon, erreur
            return $this->render('security/espace-pro.html.twig');
        }else{
            return $this->render('security/2fa_login.html.twig');
        }
    }
}
?>