<?php

namespace Belur\Cli\Commands;

use Belur\Database\Migrations\Migrator;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Belur\Helpers\app;

class Migrate extends Command {
    protected function configure() {
        $this->setName('migrate')
             ->setDescription('Run pending database migrations');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int {
        try {
            app(Migrator::class)->migrate();
            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln("<error>Could not run migrations: {$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
