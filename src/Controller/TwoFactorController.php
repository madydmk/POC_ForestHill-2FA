<?php
namespace App\Controller;

use App\Service\TwoFactorAuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TwoFactorController extends AbstractController
{
    private $tokenStorage;
    private $eventDispatcher;
    private $twoFactorAuthService;
    private $entityManager;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher,
        TwoFactorAuthService $twoFactorAuthService,
        EntityManagerInterface $entityManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
        $this->twoFactorAuthService = $twoFactorAuthService;
        $this->entityManager = $entityManager;
    }

    #[Route('/2fa', name: '2fa', methods: ['GET', 'POST'])]
    public function twoFactor(Request $request): Response
    {
        $user = $this->getUser();
        
        if (!$user) {
            throw new AccessDeniedException('User not authenticated.');
        }

        $secret = $user->getTwoFactorSecret();
        $qrCodeUrl = null;
        $error = null;

        if (!$user->isTwoFactorEnabled() && $request->isMethod('POST') && $request->request->get('setup') !== null) {
            $secret = $this->twoFactorAuthService->generateSecret();
            $user->setTwoFactorSecret($secret);
            $this->entityManager->flush();
        }

        if ($secret && !$user->isTwoFactorEnabled()) {
            $qrCodeUrl = $this->twoFactorAuthService->getQRCodeUrl($user->getEmail(), $secret);
        }

        if ($request->isMethod('POST') && $request->request->get('confirm') !== null) {
            $code = $request->request->get('code');
            if ($this->twoFactorAuthService->validateCode($secret, $code)) {
                $user->setTwoFactorEnabled(true);
                $this->entityManager->flush();
                             // Update the security token to include ROLE_2FA_COMPLETE
                $token = new UsernamePasswordToken($user, null, 'main', ['ROLE_2FA_COMPLETE']);
                $this->container->get('security.token_storage')->setToken($token);

                // Fire the login event
                $event = new InteractiveLoginEvent($request, $token);
                $this->eventDispatcher->dispatch($event, 'security.interactive_login');

                return $this->redirectToRoute('espace_pro');
            } else {
                $error = 'Invalid 2FA code.';
            }
        }

        return $this->render('security/2fa.html.twig', [
            'qrCodeUrl' => $qrCodeUrl,
            'error' => $error,
            'isTwoFactorEnabled' => $user->isTwoFactorEnabled(),
        ]);
    }

    private function authenticateUser($user, Request $request, string $firewallName): void
    {
        $token = new UsernamePasswordToken($user, $firewallName, $user->getRoles());
        $this->tokenStorage->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch($event);
    }
}
