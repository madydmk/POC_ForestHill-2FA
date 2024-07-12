<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegisterController extends AbstractController
{
    private $tokenStorage;
    private $eventDispatcher;

    public function __construct(TokenStorageInterface $tokenStorage, EventDispatcherInterface $eventDispatcher)
    {
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
    }

    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();

        if (!$session->has('attempts')) {
            $session->set('attempts', 0);
            $session->set('last_attempt_time', time());
        }

        if ($request->isMethod('POST')) {
            $attempts = $session->get('attempts');
            $lastAttemptTime = $session->get('last_attempt_time');

            $session->set('attempts', $attempts + 1);
            $session->set('last_attempt_time', time());

            $profile = htmlspecialchars($request->request->get('profile'), ENT_QUOTES, 'UTF-8');
            $firstName = htmlspecialchars($request->request->get('first_name'), ENT_QUOTES, 'UTF-8');
            $lastName = htmlspecialchars($request->request->get('last_name'), ENT_QUOTES, 'UTF-8');
            $dateOfBirth = htmlspecialchars($request->request->get('date_of_birth'), ENT_QUOTES, 'UTF-8');
            $email = htmlspecialchars($request->request->get('email'), ENT_QUOTES, 'UTF-8');
            $password = $request->request->get('password');
            
            $user = new User();
            $user->setEmail($email);
            $user->setTwoFactorEnabled(false);
            $user->setPassword(
                $passwordHasher->hashPassword($user, $password)
            );
            $entityManager->persist($user);
            $entityManager->flush();

            // Authentifier l'utilisateur
            $this->authenticateUser($user, $request, 'main'); // Utiliser le nom correct du firewall

            // Rediriger vers la configuration 2FA
            return $this->redirectToRoute('2fa_setup');
        }

        return $this->render('register.html.twig');
    }

    private function authenticateUser(User $user, Request $request, string $firewallName)
    {
        $token = new UsernamePasswordToken($user, $firewallName, $user->getRoles());
        $this->tokenStorage->setToken($token);

        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch($event);
    }
}
