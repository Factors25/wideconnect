<?php

namespace App\Service\Security;

use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityConstant
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public function getRoles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
            self::ROLE_SUPER_ADMIN,
        ];
    }

    public function getTranslatedRoles(): array
    {
        return [
            $this->translator->trans(self::ROLE_USER, domain: 'security'),
            $this->translator->trans(self::ROLE_ADMIN, domain: 'security'),
            $this->translator->trans(self::ROLE_SUPER_ADMIN, domain: 'security')
        ];
    }

    public function getSyncRoles(bool $reverse = false): array
    {
        $syncRoles = [];
        $roles = $this->getRoles();
        $translatedRoles = $this->getTranslatedRoles();

        foreach ($roles as $key => $role) {
            $syncRoles[$role] = $translatedRoles[$key];
        }

        return $reverse ? array_flip($syncRoles) : $syncRoles;
    }
}