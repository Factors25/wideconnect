<?php

namespace App\Types;

use App\Service\Sanitizer\SanitizerService;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function is_scalar;

class WideconnectTextareaType extends TextareaType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        if (!$options['sanitize_html'] && !$options['sanitizer']) {
            return;
        }

        // Before render form
        $this->addEventListener(FormEvents::PRE_SET_DATA, $builder, $options);

        // Before submit form
        $this->addEventListener(FormEvents::PRE_SUBMIT, $builder, $options);
    }

    private function addEventListener(string $event, FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(
            $event,
            function (FormEvent $event) use ($options) {
                if (is_scalar($data = $event->getData()) && '' !== trim($data)) {
                    $sanitized = SanitizerService::_sanitize(html: $data, sanitizer: $options['sanitizer']);
                    $event->setData($sanitized);
                }
            },
            10000 /* as soon as possible */
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'sanitize_html' => false,
                'sanitizer' => null
            ])
            ->setAllowedTypes('sanitize_html', 'bool')
            ->setAllowedTypes('sanitizer', ['string', 'null']);
    }
}