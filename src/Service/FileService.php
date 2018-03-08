<?php

namespace App\Service;

use App\Entity\Task;

class FileService
{

    private $tasksFileName = 'data/tasks.json';
    private $logFileName = 'data/log.json';

    /**
     * FileService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $tasks
     * @return bool
     */
    public function saveTaskModels($tasks = array())
    {
        if (!empty($tasks)) {
            $rows = array();
            foreach ($tasks as $task) {
                $rows[] = [
                    "id" => $task->getId(),
                    "desc" => $task->getDescription(),
                    "date" => $task->getCreationDate(),
                    "status" => $task->getStatus(),
                ];
            }
            $inp = file_get_contents($this->tasksFileName);
            $tempArray = json_decode($inp);
            $beforeTempArrayCount = count($tempArray);
            if (!empty($tempArray)) {
                foreach ($rows as $row) {
                    array_push($tempArray, $row);
                    $jsonData = json_encode($tempArray);
                    file_put_contents($this->tasksFileName, $jsonData);
                    $this->addActionLog($row['id'], 'create');

                }
            } else {
                $tempArray = array();
                foreach ($rows as $row) {
                    array_push($tempArray, $row);
                    $jsonData = json_encode($tempArray);
                    file_put_contents($this->tasksFileName, $jsonData);
                    $this->addActionLog($row['id'], 'create');
                }
            }
            $inp = file_get_contents($this->tasksFileName);
            $tempArray = json_decode($inp);
            if (count($tempArray) > $beforeTempArrayCount) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @return array Task
     */
    public function getTaskModels()
    {
        $tasks = array();
        $inp = file_get_contents($this->tasksFileName);
        $datas = json_decode($inp);
        if (!empty($datas)) {
            foreach ($datas as $data) {
                $task = new Task();
                $task->setId($data->id);
                $task->setDescription($data->desc);
                $task->setCreationDate($data->date);
                $task->setStatus($data->status);

                $tasks[] = $task;
            }
        }
        return $tasks;
    }

    /**
     * @param $id
     * @return Task|bool
     */
    public function getTaskModelById($id)
    {
        $inp = file_get_contents($this->tasksFileName);
        $datas = json_decode($inp);
        if (!empty($datas)) {
            foreach ($datas as $data) {
                if ($data->id == $id) {
                    $task = new Task();
                    $task->setId($data->id);
                    $task->setDescription($data->desc);
                    $task->setCreationDate($data->date);
                    $task->setStatus($data->status);
                    return $task;
                }
            }
        }
        return false;
    }

    /**
     * @return int
     */
    public function getNexTaskModelId()
    {
        $inp = file_get_contents($this->tasksFileName);
        $datas = json_decode($inp);
        if (!empty($datas)) {
            $last = $datas[count($datas) - 1];

            return $last->id + 1;
        }
        return 1;

    }

    /**
     * @param Task $task
     * @return bool
     */
    public function updateTaskModelStatus(Task $task = null)
    {
        if (!empty($task)) {
            $inp = file_get_contents($this->tasksFileName);
            $datas = json_decode($inp);
            foreach ($datas as $data) {
                if ($data->id == $task->getId()) {
                    $this->addActionLog($task->getId(), 'update-status', array($data->status, $task->getStatus()));
                    $data->status = $task->getStatus();

                }
            }
            $newJsonString = json_encode($datas);
            file_put_contents($this->tasksFileName, $newJsonString);
            return true;
        }
        return false;

    }

    private function addActionLog($taskId = null, $action = '', $args = array())
    {
        if (!empty($taskId) && !empty($action)) {
            $inp = file_get_contents($this->logFileName);
            $datas = json_decode($inp);
            if (empty($datas)) {
                $datas = array();
            }
            $row = array();
            switch ($action) {
                case 'create':
                    $row[] = [
                        'id' => $taskId,
                        'action' => $action,
                        'date' => date('c'),
                    ];
                    break;
                case 'update-status':
                    if (!empty($args) && count($args) == 2) {
                        $row[] = [
                            'id' => $taskId,
                            'action' => $action,
                            'change' => $args[0] . ' ---> ' . $args[1],
                            'date' => date('c'),
                        ];
                    } else {
                        $row[] = [
                            'id' => $taskId,
                            'action' => $action,
                            'change' => 'MISSING ARGUMENTS',
                            'date' => date('c'),
                        ];
                    }
                    break;
            }

            if (!empty($row)) {
                array_push($datas, $row);
                $jsonData = json_encode($datas);
                file_put_contents($this->logFileName, $jsonData);
                return true;
            }
            return false;

        }

        return false;
    }

    /**
     * @return array|mixed
     */
    public function getLogModels()
    {
        $datas = array();
        if (file_exists($this->logFileName)) {
            $inp = file_get_contents($this->logFileName);
            $datas = json_decode($inp);
        }
        $logs = array();
        if(!empty($datas)) {
            foreach ($datas as $data) {
                $data = $data[0];
                $change = (isset($data->change)) ? $data->change : '';
                $logs[] = [
                    'id' => $data->id,
                    'action' => $data->action,
                    'change' => $change,
                    'date' => $data->date,
                ];
            }
        }

        return $logs;


    }

}