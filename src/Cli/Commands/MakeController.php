<?php

namespace Belur\Cli\Commands;

use Belur\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Belur\Helpers\resourcesDirectory;

class MakeController extends Command {
    protected function configure() {
        $this->setName('make:controller')
             ->setDescription('Create new controller file')
             ->addArgument('name', InputArgument::REQUIRED, 'The name of the controller');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int {
        $name = $input->getArgument('name');
        $template = file_get_contents(resourcesDirectory() . '/templates/controller.php');
        $template = str_replace('{{controllerName}}', $name, $template);
        file_put_contents(App::$root . '/app/Controllers/' . $name . '.php', $template);
        $output->writeln("<info>Controller $name created successfully.</info>");

        return Command::SUCCESS;
    }
}
