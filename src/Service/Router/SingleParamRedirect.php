<?php

namespace App\Service\Router;

class SingleParamRedirect
{
    private string $route;
    private ?string $parameter;

    public function __construct(string $route, ?string $parameter)
    {
        $this->route = $route;
        $this->parameter = $parameter;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getParameter(): ?string
    {
        return $this->parameter;
    }
}