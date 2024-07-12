<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TwoFactorAuthService;
use Doctrine\ORM\EntityManagerInterface;

class TwoFactorController extends AbstractController
{
    private $twoFactorAuthService;
    private $entityManager;

    public function __construct(TwoFactorAuthService $twoFactorAuthService, EntityManagerInterface $entityManager)
    {
        $this->twoFactorAuthService = $twoFactorAuthService;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/2fa/setup", name="2fa_setup")
     */
    #[Route("/2fa/setup", name:"2fa_setup")]
    public function setup(Request $request)
    {
        $user = $this->getUser();

        if ($user->isTwoFactorEnabled()) {
            return $this->redirectToRoute('2fa_confirm');
        } else if ($request->isMethod('POST')) {
            $secret = $this->twoFactorAuthService->generateSecret();
            $user->setTwoFactorSecret($secret);
            $this->entityManager->flush();

            return $this->redirectToRoute('2fa_confirm');
        }
        return $this->render('security/2fa_setup.html.twig');
    }

    /**
     * @Route("/2fa/confirm", name="2fa_confirm")
     */
    #[Route("/2fa/confirm", name:"2fa_confirm")]
    public function confirm(Request $request)
    {
        $user = $this->getUser();
        $secret = $user->getTwoFactorSecret();
        $qrCodeUrl = $this->twoFactorAuthService->getQRCodeUrl($user->getEmail(), $secret);
        $error = null;

        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
            if ($this->twoFactorAuthService->validateCode($secret, $code)) {
                $user->setTwoFactorEnabled(true);
                $this->entityManager->flush();
                return $this->redirectToRoute('espace_pro');
            } else {
                $error = 'Invalid 2FA code.';
            }
        }

        return $this->render('security/2fa_confirm.html.twig', [
            'qrCodeUrl' => $qrCodeUrl,
            'error' => $error,
        ]);
    }
}
