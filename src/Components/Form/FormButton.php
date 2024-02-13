<?php

namespace App\Components\Form;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\TwigComponent\Attribute\PreMount;

#[AsTwigComponent(
    name: 'FormButton',
    template: 'components/twig/form/form_button.html.twig'
)]
class FormButton
{
    public string $type;
    public ?string $label;
    public ?string $size;

    #[PreMount]
    public function preMount(array $data): array
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(['type']);
        $resolver->setDefaults(['label' => null, 'size' => 'lg']);

        $resolver->setAllowedValues('type', ['light', 'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'dark']);

        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('label', ['string', 'null']);
        $resolver->setAllowedTypes('size', ['string']);

        return $resolver->resolve($data);
    }
}