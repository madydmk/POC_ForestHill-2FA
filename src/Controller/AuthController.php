<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\TwoFactorAuthService;

class AuthController extends AbstractController
{
    private $twoFactorAuthService;

    public function __construct(TwoFactorAuthService $twoFactorAuthService)
    {
        $this->twoFactorAuthService = $twoFactorAuthService;
    }

    // #[Route('/register', name: 'register', methods: ['POST'])]
    // public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    // {
    //     $data = json_decode($request->getContent(), true);
    //     $email = htmlspecialchars($data['email'], ENT_QUOTES, 'UTF-8');
    //     $password = $data['password'];

    //     $user = new User();
    //     $user->setEmail($email);
    //     $user->setPassword(
    //         $passwordHasher->hashPassword($user, $password)
    //     );

    //     $entityManager->persist($user);
    //     $entityManager->flush();

    //     return new Response("User registered successfully!", 201);
    // }

    #[Route('/2fa', name: '2fa_login')]
     public function twoFactor(Request $request)
    {
        $error = null;
        $user = $this->getUser();

        if (!$user->getTwoFactorSecret()) {
            // Générer le secret 2FA pour l'utilisateur s'il n'existe pas
            $secret = $this->twoFactorAuthService->generateSecret();
            $user->setTwoFactorSecret($secret);
            $this->getDoctrine()->getManager()->flush();

            $qrCodeUrl = $this->twoFactorAuthService->getQRCodeUrl($user->getUsername(), $secret);
        } else {
            $qrCodeUrl = null;
        }

        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            if ($this->twoFactorAuthService->validateCode($user, $code)) {
                return $this->redirectToRoute('dashboard');
            } else {
                $error = 'Invalid 2FA code.';
            }
        }

        return $this->render('security/2fa_login.html.twig', [
            'error' => $error,
            'qrCodeUrl' => $qrCodeUrl,
        ]);
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    #[Route('/dashboard', name: 'dashboard', methods: ['GET'])]
    public function dashboard()
    {
        return $this->render('dashboard.html.twig');
    }
}
