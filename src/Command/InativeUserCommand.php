<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Manager\ManagerService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'wideconnect:inactive-users',
    description: 'Inactive users after inactivity',
)]
class InativeUserCommand extends Command
{
    public function __construct(private readonly UserRepository $userRepository, private readonly ManagerService $managerService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findInactive();

        /** @var User $user */
        foreach($users as $user) {
            $roles = $user->getRoles();
            if(
                in_array('ROLE_ADMIN', $roles, true) ||
                in_array('ROLE_SUPER_ADMIN', $roles, true)
            ) {
                continue;
            }
            $this->managerService->delete($user);
        }

        $io->success('Users purged OK!');
        return Command::SUCCESS;
    }
}
