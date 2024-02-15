<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivacyPolicyController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route(path: '/privacy-policy', name: 'privacy_policy')]
    public function index(): Response
    {
        return $this->render('security/privacy-policy/privacy_policy.html.twig');
    }
}