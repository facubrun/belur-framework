<?php

namespace Belur\Cli\Commands;

use Belur\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Belur\Helpers\app;

class MigrateRollback extends Command {
    protected function configure() {
        $this->setName('migrate:rollback')
             ->setDescription('Rollback migrations')
             ->addArgument('steps', InputArgument::OPTIONAL, 'The number of steps to rollback');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            $steps = $input->getArgument('steps');
            $fileName = app(Migrator::class)->rollback($steps);
            $output->writeln("<info>Migration rolled back: $fileName</info>");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Could not reverse migrations: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
