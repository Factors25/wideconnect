<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'WIDECONNECT:debug',
    description: 'Research debug code in whole project',
)]
class DebugCommand extends Command
{
    public function __construct(#[Autowire('%kernel.project_dir%')] private string $projectDir)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $debugElement = 0;

        $finder = new Finder();
        $files = $finder->files()
            ->name('*.php')
            ->name('*.html.twig')
            ->exclude(['var', 'vendor', 'node_modules', 'src/Command', 'migrations', 'src/DataFixtures'])
            ->in($this->projectDir);

        foreach ($files as $file) {
            $filePath = $file->getRealPath();
            $lines = file($filePath);

            foreach ($lines as $lineNumber => $line) {
                $matches = [];

                if (preg_match('/\bdd\(/', $line, $matches)) {
                    $debugElement++;
                    $io->writeln(sprintf('Fichier: %s, Ligne: %d (dd())', $filePath, $lineNumber + 1));
                }

                if (preg_match('/\bdump\(/', $line, $matches)) {
                    $debugElement++;
                    $io->writeln(sprintf('Fichier: %s, Ligne: %d (dump())', $filePath, $lineNumber + 1));
                }

                if (preg_match('/\bdie\(/', $line, $matches)) {
                    $debugElement++;
                    $io->writeln(sprintf('Fichier: %s, Ligne: %d (die())', $filePath, $lineNumber + 1));
                }

                if (preg_match('/\becho/', $line, $matches)) {
                    $debugElement++;
                    $io->writeln(sprintf('Fichier: %s, Ligne: %d (echo)', $filePath, $lineNumber + 1));
                }

                if (preg_match('/\bprint_r\(/', $line, $matches)) {
                    $debugElement++;
                    $io->writeln(sprintf('Fichier: %s, Ligne: %d (print_r())', $filePath, $lineNumber + 1));
                }
            }
        }

        if ($debugElement > 0) {
            $io->error('Debug statement detected!');
            return Command::FAILURE;
        }

        $io->success('Debug statement OK!');
        return Command::SUCCESS;
    }
}
