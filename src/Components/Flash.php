<?php

namespace App\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(
    name: 'Flash',
    template: 'components/twig/flash.html.twig'
)]
class Flash
{
    public mixed $errors;
}