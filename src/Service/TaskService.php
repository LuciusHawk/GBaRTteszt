<?php

namespace App\Service;

use App\Entity\Task;

class TaskService extends Task
{

    /**
     * TaskService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $description
     * @return Task|bool
     */
    public function createTask($description = '')
    {
        if (!empty($description)) {
            $task = new Task();
            $fileService = new FileService();

            $task->setId($fileService->getNexTaskModelId());
            $task->setDescription($description);
            $task->setCreationDate(date('c'));
            $task->setStatus('NEW');
            if ($fileService->saveTaskModels(array($task))) {
                return $task;
            }
            return false;
        }
        return false;

    }

    /**
     * @param null $id
     * @param string $status
     * @return array|bool
     */
    public function updateTaskStatus($id = null, $status = '')
    {
        if (!empty($id) && !empty($status)) {
            $fileService = new FileService();
            $task = $fileService->getTaskModelById($id);
            $originalTaskStatus = $task->getStatus();
            if($task->checkStatusMovementIsCorrect($status)) {
                $task->setStatus($status);
                $fileService->updateTaskModelStatus($task);
                return array($task->getId(), $originalTaskStatus, $task->getStatus(), true);
            }else {
                return array($task->getId(), $originalTaskStatus, $task->getStatus(), false);
            }

        }
        return false;
    }

}