<?php

namespace App\Form\User;

use App\Entity\User;
use App\Service\Entity\User\UserPlaceholder;
use App\Service\Security\SecurityConstant;
use App\Types\WideconnectTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function __construct(private readonly SecurityConstant $securityConstant)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $edit = !($builder->getData()->getId() === null);

        $builder
            ->add('username', WideconnectTextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [...UserPlaceholder::USERNAME],
                'sanitize_html' => true,
                'sanitizer' => 'app.clean'
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => ['placeholder' => 'alexis.py@gmail.com']
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'label' => 'Rôle(s)',
                'choices' => $this->securityConstant->getSyncRoles(reverse: true),
                'expanded' => true,
                'multiple' => true
            ])
            ->add('active', CheckboxType::class, [
                'required' => false,
                'label' => 'Activer ?'
            ]);

        // add
        if (!$edit) {
            $this->actionCreate($builder);
        } else {
            $this->actionUpdate($builder);
        }
    }

    public function actionCreate(FormBuilderInterface $builder): void
    {
        $builder->add('password', RepeatedType::class, [
            'required' => true,
            'type' => PasswordType::class,
            'first_options' => [
                'label' => 'Mot de passe',
                'help' =>
                    'Au moins 10 caractères.<br>' .
                    'Au moins une lettre minuscule.<br>' .
                    'Au moins une lettre majuscule.<br>' .
                    'Au moins un chiffre.<br>' .
                    'Au moins un caractère spécial.',
                'attr' => [...UserPlaceholder::PASSWORD],
                'help_html' => true
            ],
            'second_options' => [
                'label' => 'Confirmation mot de passe',
                'attr' => [...UserPlaceholder::PASSWORD]
            ],
            'invalid_message' => 'Les mots de passes semblent différent.'
        ]);
    }

    public function actionUpdate(FormBuilderInterface $builder): void
    {
        $builder->add('editPassword', CheckboxType::class, [
            'required' => false,
            'label' => 'Changer le mot de passe ?',
            'mapped' => false,
            'attr' => [
                'data-formevent' => '',
                'data-dom-edit-password' => ''
            ]
        ]);

        $builder
            ->get('editPassword')
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onEditPassword']);
    }

    public function onEditPassword(FormEvent $formEvent): void
    {
        $form = $formEvent->getForm()?->getParent();
        $data = (bool)$formEvent->getData();

        if ($data === true) {
            $form?->add('password', RepeatedType::class, [
                'required' => true,
                'mapped' => false,
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'help' =>
                        'Au moins 10 caractères.<br>' .
                        'Au moins une lettre minuscule.<br>' .
                        'Au moins une lettre majuscule.<br>' .
                        'Au moins un chiffre.<br>' .
                        'Au moins un caractère spécial.',
                    'attr' => [...UserPlaceholder::PASSWORD],
                    'help_html' => true
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [...UserPlaceholder::PASSWORD]
                ],
                'invalid_message' => 'Les mots de passes semblent différent.',
                'constraints' => [
                    new Length(min: 10, minMessage: 'Votre mot de passe doit contenir au moins 10 caractères.'),
                    new Regex(pattern: '/[a-z]/', message: 'Votre mot de passe doit contenir au moins une lettre minuscule.'),
                    new Regex(pattern: '/[A-Z]/', message: 'Votre mot de passe doit contenir au moins une lettre majuscule.'),
                    new Regex(pattern: '/[0-9]/', message: 'Votre mot de passe doit contenir au moins un chiffre.'),
                    new Regex(pattern: '/\W/', message: 'Votre mot de passe doit contenir au moins un caractère spécial.')
                ]
            ]);
        } else {
            $form?->remove('password');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}