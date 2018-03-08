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
            ->setHelp('Manage TODO tasks with console commands. 
            List tasks: --mode=list . 
            Create task: --mode=create "Description to task" . 
            Update status: --mode=update-status ID "STATUS" (Status could be "NEW"|"INPROGRESS"|"ONHOLD"|"DONE")')
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

        switch ($mode) {
            case 'list':
                $fileService = new FileService();
                $tasks = $fileService->getTaskModels();
                $this->printList($tasks);
                break;
            case 'create':
                $this->createTask($options);
                break;
            case 'update-status':
                $this->updateStatus($options);
                break;
            default:
                $fileService = new FileService();
                $tasks = $fileService->getTaskModels();
                $this->printList($tasks);
                break;
        }

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

    protected function createTask($options = array())
    {

        if (count($options) == 1) {
            $taskService = new TaskService();
            $task = $taskService->createTask($options[0]);
            echo "Created task: id: " . $task->getId() . ' -> ' . $task->getDescription() . ": " . $task->getStatus() . "\n";
            echo "__________________________\n";
        } else {
            echo 'Type only the task "description" for the create.';
        }

    }

    protected function updateStatus($options = array())
    {
        if (!empty($options) && count($options) == 2 && is_numeric($options[0]) && $this->checkCorrectStatus($options[1])) {
            $id = $options[0];
            $status = $options[1];
            $taskService = new TaskService();

            if ($update = $taskService->updateTaskStatus($id, $status)) {
                if (isset($update[3]) && $update[3]) {
                    echo "Successfully update $update[0]: $update[1] --> $update[2]";
                } else {
                    echo "Update Fail $update[0]: $update[1] --> $update[2]";
                }
            } else {
                echo 'Empty ID or Status';
            }
        } else {
            echo 'Type the chosen task ID and the new STATUS. (Status could be "NEW"|"INPROGRESS"|"ONHOLD"|"DONE")';
        }
    }

    private function checkCorrectStatus($status)
    {
        return ($status == "NEW" || $status == "INPROGRESS" || $status == "ONHOLD" || $status == "DONE");
    }
}