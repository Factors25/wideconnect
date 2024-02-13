<?php

namespace App\Service\Entity\User;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Datatable\DatatableService;
use App\Service\Dom\DomService;
use App\Twig\CustomTwigExtensions;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use JsonException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserService
{
    public const METHOD_PRE_CREATE = 'preCreate';
    public const METHOD_PRE_UPDATE = 'preUpdate';
    public const METHOD_DATATABLE = 'datatable';
    public const METHOD_API = 'api';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly DatatableService            $datatableService,
        private readonly UserRepository              $userRepository,
        private readonly TranslatorInterface         $translator,
        private readonly UserAction                  $userAction,
        private readonly CustomTwigExtensions        $customTwigExtensions
    )
    {
    }

    /**
     * @throws JsonException
     */
    public function api(Request $request, array $parameters = []): false|string
    {
        /** @var User $user */
        $user = $parameters['user'];

        $data = [
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $this->getRoles($user),
            'active' => $this->customTwigExtensions->custom_boolean_filter($user->isActive()),
            'verify' => $this->customTwigExtensions->custom_boolean_filter($user->isVerify()),
            'created' => $this->customTwigExtensions->custom_created_filter($user),
            'updated' => $this->customTwigExtensions->custom_updated_filter($user),
            'deleted' => $this->customTwigExtensions->custom_deleted_filter($user),
            'archived' => $this->customTwigExtensions->custom_archived_filter($user),
            'actionUpdate' => $this->userAction->updateButton($user),
            'actionDelete' => $this->userAction->deleteButton($user)
        ];

        return json_encode(
            [
                'user' => $data
            ], JSON_THROW_ON_ERROR
        );
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     * @throws JsonException
     */
    public function datatable(Request $request, array $parameters = []): false|string
    {
        $parameters = [...$this->datatableService->init($request), ...$parameters];

        $results = $this->userRepository->datatable($parameters);

        $objects = array_slice($results['result'], $parameters['start'], $parameters['length']);

        $responseData = [];

        /** @var User $user */
        foreach ($objects as $user) {
            $data = [];

            foreach ($parameters['columns'] as $column) {
                $columnData = match ($column['name']) {
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                    'roles' => $this->getRoles($user),
                    'active' => $user->isActive() ? 'Yes' : 'No',
                    'verify' => $user->isVerify() ? 'Yes' : 'No',
                    'actions' => $this->getDatatableActions($user)
                };

                $data[] = $columnData;
            }

            $responseData[] = $data;
        }

        return json_encode(
            [
                'draw' => $parameters['draw'],
                'recordsTotal' => $this->userRepository->count([]),
                'recordsFiltered' => $results['count'],
                'data' => $responseData
            ], JSON_THROW_ON_ERROR
        );
    }

    public function getRoles(User $user): string
    {
        $data = '';
        $roles = $user->getRoles();
        foreach ($roles as $role) {
            $data .= DomService::element(
                    tag: 'span',
                    attributes: ['class' => 'badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2'],
                    content: $this->translator->trans($role, domain: 'security')
                ) . (next($roles) ? DomService::element('br') : '');
        }

        return $data;
    }

    public function getDatatableActions(User $user): string
    {
        return DomService::element(
            tag: 'div',
            attributes: ['class' => 'd-flex justify-content-end gap-1'],
            content: [
                $this->userAction->showButton($user),
                $this->userAction->updateButton($user, false),
                $this->userAction->deleteButton($user, false)
            ]
        );
    }

    public function preCreate(
        FormInterface $form,
        User          $user,
        array         $parameters = []
    ): void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        $user->setVerify(true);
    }

    public function preUpdate(
        FormInterface $form,
        User          $user,
        array         $parameters = []
    ): void
    {
        if ($form->has('password')) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $form->get('password')->getData()));
            $user->setVerify(true);
        } else {
            $user->setPassword($parameters['oldPassword']);
        }
    }
}