<?php

namespace App\Controller\Home;

use App\Service\Generic\CrudGeneric;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends CrudGeneric
{
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $this->index_template = 'navigation/home/index.html.twig';

        return $this->_index($request);
    }
}
