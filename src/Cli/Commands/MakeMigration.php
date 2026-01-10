<?php

namespace Belur\Cli\Commands;

use Belur\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Belur\Helpers\app;

class MakeMigration extends Command {
    protected function configure() {
        $this->setName('make:migration')
             ->setDescription('Create new migration file')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the migration');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $fileName = app(Migrator::class)->make($input->getArgument('name'));
        $output->writeln("<info>Migration created: $fileName</info>");
        return Command::SUCCESS;
    }
}
