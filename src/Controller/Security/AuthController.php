<?php

namespace App\Controller\Security;

use App\Service\Validation\Error\TranslatedError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $lastError = $authenticationUtils->getLastAuthenticationError();
        $errors = [];

        if (
            $lastError instanceof BadCredentialsException ||
            $lastError instanceof UserNotFoundException
        ) {
            $errors[] = new TranslatedError('user.bad.credentials');
        }

        return $this->render('security/login/login.html.twig', [
            'errors' => $errors
        ]);
    }

    /**
     * @return Response
     */
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }
}