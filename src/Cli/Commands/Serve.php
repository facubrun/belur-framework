<?php

namespace Belur\Cli\Commands;

use Belur\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Serve extends Command {
    protected function configure() {
        $this->setName('serve')
             ->setDescription('Start the development server')
             ->addOption('host', null, InputOption::VALUE_OPTIONAL, 'The host address', '127.0.0.1')
             ->addOption('port', null, InputOption::VALUE_OPTIONAL, 'The port number', '8000');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $host = $input->getOption('host');
        $port = $input->getOption('port');
        $dir = App::$root . '/public';

        $output->writeln("<info>Starting development server at http://$host:$port</info>");
        shell_exec("php -S $host:$port -t $dir/index.php");

        return Command::SUCCESS;
    }
}
