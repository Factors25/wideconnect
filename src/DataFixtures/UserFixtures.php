<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Service\Manager\ManagerService;
use App\Service\Security\SecurityConstant;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements OrderedFixtureInterface, FixtureGroupInterface
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ManagerService              $managerService
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();

        $user->setUsername('apy')
            ->setEmail('alexis25.py@gmail.com')
            ->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    '^*38]D+7(np)vlNN'
                )
            )
            ->setVerify(1)
            ->setActive(1)
            ->setLastActivityAt(new DateTimeImmutable())
            ->setRoles([SecurityConstant::ROLE_SUPER_ADMIN]);

        $this->managerService->create($user);
    }

    public static function getGroups(): array
    {
        return ['user'];
    }

    public function getOrder(): int
    {
        return 1;
    }
}
