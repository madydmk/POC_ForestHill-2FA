<?php

namespace App\Service;

use OTPHP\TOTP;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RequestStack;

class TwoFactorAuthService
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function isTwoFactorAuthenticated(): bool
    {
        $session = $this->requestStack->getSession();
        return $session->get('isTwoFactorAuthenticated', false);
    }

    public function setTwoFactorAuthenticated(bool $authenticated): void
    {
        $session = $this->requestStack->getSession();
        $session->set('isTwoFactorAuthenticated', $authenticated);
    }
    public function generateSecret(): string
    {
        $totp = TOTP::create();
        return $totp->getSecret();
    }

    public function getQRCodeUrl(string $username, string $secret): string
    {
        $totp = TOTP::create(
            $secret, // The shared secret
            30, // The period (default is 30 seconds)
            'sha1', // The digest algorithm (default is SHA1)
            6 // The number of digits (default is 6)
        );
        $totp->setLabel($username);
        $totp->setIssuer('Forest Hill');

        return $totp->getProvisioningUri();
    }

    public function validateCode(string $secret, string $code): bool
    {
        $totp = TOTP::create($secret);
        return $totp->verify($code);
    }

    public function displayQrCode(string $qrCodeContent): Response
    {
        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($qrCodeContent)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(0)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->build();

        return new Response($result->getString(), 200, ['Content-Type' => 'image/png']);
    }
    public function displayTotpQrCode(TokenStorageInterface $tokenStorage, TotpAuthenticatorInterface $totpAuthenticator): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        if (!($user instanceof TotpTwoFactorInterface)) {
            throw new NotFoundHttpException('Cannot display QR code');
        }

        return $this->displayQrCode($totpAuthenticator->getQRContent($user));
    }

    // public function isTwoFactorAuthenticated(): bool
    // {
    //     return $this->$requestStack->getSession()->get('isTwoFactorAuthenticated', false);
    // }

    // public function setTwoFactorAuthenticated(bool $status): void
    // {
    //     $this->$requestStack->getSession()->set('isTwoFactorAuthenticated', $status);
    // }

    // public function clearTwoFactorAuthenticated(): void
    // {
    //     $this->$requestStack->getSession()->remove('isTwoFactorAuthenticated');
    // }
}
