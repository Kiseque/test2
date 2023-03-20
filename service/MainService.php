<?php

namespace app\service;

use app\repository\MainRepository;
use app\dbConnect;
use mysql_xdevapi\Exception;

class MainService
{
    private MainRepository $mainRepository;
    private dbConnect $dbConnect;

    public function __construct()
    {
        $this->mainRepository = new MainRepository();
        $this->dbConnect = new dbConnect();
    }

    public function getTree()
    {
        return $this->mainRepository->getTree();
    }

    public function getRow($id)
    {
        return $this->mainRepository->getRow($id);
    }

    public function insertRow($name, $id)
    {
        $this->dbConnect->beginTransaction();
        try {
            if (count($this->mainRepository->getRow($id)) === 1) {
                $this->mainRepository->insertRow($name, $id);
            }
            $this->dbConnect->commit();
            echo "Successfull insert";
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteRow($id)
    {
        $this->dbConnect->beginTransaction();
        try {
            $this->deleteRecuirsiveRow($id);
            $this->dbConnect->commit();
            echo 'Successfull delete';
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    private function deleteRecuirsiveRow($id)
    {
        $children = array_column($this->mainRepository->getByParent($id), 'ID');
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->deleteRecuirsiveRow($child);
            }
        }
        $this->mainRepository->deleteRow($id);
    }

    private function countElems($id, $counterErase)
    {
        static $counter = 0;
        if ($counterErase) {
            $counter = 0;
        }
        if ($counter > count($this->mainRepository->getTree())) {
            return $counter;
        }
        $counter++;
        $children = array_column($this->mainRepository->getByParent($id), 'ID');
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->countElems($child, false);
            }
        }
        return $counter;
    }

    public function updateRow($name, $parent_id, $id)
    {
        $this->dbConnect->beginTransaction();
        try {
            if (isset($name) && isset($parent_id) && count($this->mainRepository->getRow($id)) === 1 && $parent_id !== $id && (count($this->mainRepository->getRow($parent_id)) === 1 || $parent_id == 0 || $parent_id == null)) {
                $temp = array_column($this->mainRepository->getRow($id), 'Parent_ID');
                $this->mainRepository->updateBoth($name, $parent_id, $id);
                if ($this->countElems(1, true) > count($this->mainRepository->getTree())) {
                    echo 'Invalid Parent_ID value';
                    $this->mainRepository->updateParent($temp[0], $id);
                } else {
                    echo 'Successfull Name and Parent_ID update';
                }
            } elseif (isset($parent_id) && empty($name) && count($this->mainRepository->getRow($id)) === 1 && $parent_id !== $id && (count($this->mainRepository->getRow($parent_id)) === 1 || $parent_id == 0 || $parent_id == null)) {
                $temp = array_column($this->mainRepository->getRow($id), 'Parent_ID');
                $this->mainRepository->updateParent($parent_id, $id);
                if ($this->countElems(1, true) > count($this->mainRepository->getTree())) {
                    echo 'Invalid Parent_ID value';
                    $this->mainRepository->updateParent($temp[0], $id);
                } else {
                    echo 'Successfull Parent_ID update';
                }
            } elseif (isset($name) && empty($parent_id) && count($this->mainRepository->getRow($id)) === 1) {
                $this->mainRepository->updateName($name, $id);
                echo "Successfull Name update";
            } else {
                echo "Error";
            }
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}