<?php

namespace App\Service\Router;

use Symfony\Component\Routing\RouterInterface;

readonly class RouterService
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function reverseUrl($url): array
    {
        $lazy_route = $this->router->match($url);
        $_route = $lazy_route['_route'];
        $_route_params = array_diff_key($lazy_route, array_flip(['_route', '_controller']));

        return [$_route, $_route_params];
    }
}