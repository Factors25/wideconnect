<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     * @return void
     */
    public function checkPreAuth(UserInterface $user): void
    {
    }

    /**
     * @param UserInterface $user
     * @return void
     */
    public function checkPostAuth(UserInterface $user): void
    {
        /** @var User $user */
        if (!$user->isActive()) {
            throw new CustomUserMessageAccountStatusException(
                message: 'Votre compte est désactivé.',
                code: 403
            );
        }

        if ($user->isArchived()) {
            throw new CustomUserMessageAccountStatusException(
                message: 'Votre compte est archivé.',
                code: 403
            );
        }
    }
}