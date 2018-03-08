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

        }
    }

    /**
     * @return array
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

}