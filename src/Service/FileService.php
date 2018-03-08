<?php

namespace App\Service;

use App\Entity\Task;

class FileService
{

    private $fileName = 'data/tasks.json';

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
            $inp = file_get_contents($this->fileName);
            $tempArray = json_decode($inp);
            $beforeTempArrayCount = count($tempArray);
            if (!empty($tempArray)) {
                foreach ($rows as $row) {
                    array_push($tempArray, $row);
                    $jsonData = json_encode($tempArray);
                    file_put_contents($this->fileName, $jsonData);
                }
            } else {
                $tempArray = array();
                foreach ($rows as $row) {
                    array_push($tempArray, $row);
                    $jsonData = json_encode($tempArray);
                    file_put_contents($this->fileName, $jsonData);
                }
            }
            $inp = file_get_contents($this->fileName);
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
        $inp = file_get_contents($this->fileName);
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
        $inp = file_get_contents($this->fileName);
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
        $inp = file_get_contents($this->fileName);
        $datas = json_decode($inp);
        $last = $datas[count($datas) - 1];
        return $last->id + 1;
    }

    /**
     * @param Task $task
     * @return bool
     */
    public function updateTaskModelStatus(Task $task)
    {
        $inp = file_get_contents($this->fileName);
        $datas = json_decode($inp);
        foreach ($datas as $data) {
            if($data->id == $task->getId()) {
                $data->status = $task->getStatus();
            }
        }
        $newJsonString = json_encode($datas);
        file_put_contents($this->fileName, $newJsonString);
        return false;
    }

}