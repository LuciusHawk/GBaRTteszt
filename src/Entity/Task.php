<?php

namespace App\Entity;

class Task
{
    private $id;
    private $description;
    private $creation_date;
    private $status;

    /**
     * Task constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * @param mixed $creation_date
     */
    public function setCreationDate($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     */
    public function setStatus($status)
    {
        if ($this->checkStatusMovementIsCorrect($status)) {
            $this->status = $status;
        }
    }

    private function checkStatusMovementIsCorrect($status)
    {
        if (isset($this->status) && !empty($this->status)) {
            if ($this->status == 'NEW' && $status == 'INPROGRESS' ||
                $this->status == 'INPROGRESS' && $status == 'ONHOLD' ||
                $this->status == 'INPROGRESS' && $status == 'DONE' ||
                $this->status == 'ONHOLD' && $status == 'INPROGRESS' ||
                $this->status == 'ONHOLD' && $status == 'DONE') {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }


}