<?php

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

readonly class SecurityService
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function generateVerifyUserUrl(string $path, User $user): string
    {
        $args = '?token=' . $user->getToken()['signature'] . '&expired=' . $user->getToken()['expires'] . '&email=' . $user->getEmail();

        return $this->generateUrl($path, $args);
    }

    public function generateUrl($path, $args): string
    {
        return $this->router->generate($path, [], UrlGeneratorInterface::ABSOLUTE_URL) . $args;
    }
}