<?php

namespace App\Command;

use App\Entity\Task;
use App\Service\FileService;
use App\Service\TaskService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class TodoCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('TODO')
            // the short description shown while running "php bin/console list"
            ->setDescription('Manage TODO tasks')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('Manage TODO tasks with console commands')
            ->addArgument(
                'arguments',
                InputArgument::IS_ARRAY | InputArgument::OPTIONAL,
                'Id "Description" "Status" (separate multiple names with a space, mark "*" if you don\'t want to change that field)'
            )
            ->addOption(
                'mode',
                'm',
                InputOption::VALUE_REQUIRED,
                'List tasks: list, Create task: create, Update status: update-status',
                'list'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getOption('mode');
        $options = $input->getArgument('arguments');
        var_dump($mode);
        if (count($options) > 0) {
            var_dump($options);
        }
        switch ($mode) {
            case 'list':
                $fileService = new FileService();
                $tasks = $fileService->getTaskModels();
                $this->printList($tasks);
                break;
            case 'create':
                echo 'Create this: '."\n";
                if (count($options) > 0) {
                    var_dump($options);
                }
                break;
            case 'update-status':
                $mode = $input->getOption('mode');
                var_dump($mode);
                break;
            default:
                $fileService = new FileService();
                $tasks = $fileService->getTaskModels();
                $this->printList($tasks);
                break;
        }



//
//        $task = new Task();
//        $task->setId(1);
//        $task->setDescription('Ez is véget ér egyszer');
//        $task->setCreationDate(date('c'));
//        $task->setStatus('NEW');
//        $fileService->saveTaskModels(array(
//            $task,
//        ));



    }

    protected function printList($tasks = array())
    {
        echo "|ID   ||Description  ||Creation date ||Status    |\n";
        echo "===================================================\n";
        if (!empty($tasks)) {
            foreach ($tasks as $task) {
                if ($task instanceof Task) {
                    echo '|' . $task->getId() . "   |";
                    echo '|' . $task->getDescription() . "   |";
                    echo '|' . $task->getCreationDate() . "   |";
                    echo '|' . $task->getStatus() . "   |\n";
                }
            }
        }
    }
}