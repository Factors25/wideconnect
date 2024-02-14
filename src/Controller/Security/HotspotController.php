<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HotspotController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route(path: '/hotspot', name: 'hotspot')]
    public function index(): Response
    {
        return $this->render('security/hotspot/hotspot.html.twig');
    }
}