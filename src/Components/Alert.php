<?php

namespace App\Components;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(
    name: 'Alert',
    template: 'components/twig/alert.html.twig'
)]
class Alert
{
    public string $title;
    public string $message;
    public string $color;
    public ?string $icon;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['title', 'message', 'color']);
        $resolver->setDefault('icon', null);

        $resolver->setAllowedValues('color', ['light', 'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark']);

        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('message', 'string');
        $resolver->setAllowedTypes('color', 'string');
        $resolver->setAllowedTypes('icon', ['string', 'null']);

        return $resolver->resolve($data);
    }
}