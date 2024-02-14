<?php

namespace App\Form\Security;

use App\Entity\User;
use App\Service\Entity\User\UserPlaceholder;
use App\Types\WideconnectTextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', WideconnectTextType::class, [
                'required' => true,
                'label' => 'Login',
                'attr' => [...UserPlaceholder::USERNAME],
                'sanitize_html' => true,
                'sanitizer' => 'app.clean'
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Email',
                'attr' => ['placeholder' => 'alexis25.py@gmail.com']
            ])
            ->add('password', RepeatedType::class, [
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
                    'sanitize_html' => true,
                    'sanitizer' => 'app.clean'
                ],
                'second_options' => [
                    'label' => 'Confirmation mot de passe',
                    'attr' => [...UserPlaceholder::PASSWORD],
                    'sanitize_html' => true,
                    'sanitizer' => 'app.clean'
                ],
                'invalid_message' => 'Les mots de passes semblent différent.'
            ])
            ->add('toc', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les termes et conditions.',
                    ]),
                ],
                'label' => 'J\'accepte les termes et conditions.'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}