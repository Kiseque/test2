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

    private function treeLoopCheck($parentId, $id)
    {
        $parent = $this->mainRepository->getRow($parentId);
        $result = true;
        if (!empty($parent)) {
            foreach ($parent as $value) {
                $parentId = $value['Parent_ID'];
                if ($id == $parentId) {
                    $result = false;
                }
                if (!$this->treeLoopCheck($parentId, $id)) {
                    $result = false;
                }
            }
        }
        return $result;
    }

    public function updateRow($name, $parentId, $id)
    {
        $this->dbConnect->beginTransaction();
        try {
            if (isset($name) && isset($parentId) && count($this->mainRepository->getRow($id)) === 1 && $parentId !== $id && (count($this->mainRepository->getRow($parentId)) === 1 || $parentId == 0 || $parentId == null)) {
                $temp = array_column($this->mainRepository->getRow($id), 'Parent_ID');
                $this->mainRepository->updateBoth($name, $parentId, $id);
                if ($this->countElems(1, true) > count($this->mainRepository->getTree())) {
                    echo 'Invalid Parent_ID value';
                    $this->mainRepository->updateParent($temp[0], $id);
                } else {
                    echo 'Successfull Name and Parent_ID update';
                }
            } elseif (isset($parentId) && empty($name) && count($this->mainRepository->getRow($id)) === 1 && $parentId !== $id && (count($this->mainRepository->getRow($parentId)) === 1 || $parentId == 0 || $parentId == null)) {
                if ($this->treeLoopCheck($parentId, $id)) {
                    echo 'Nice';
                } else {
                    echo 'Fuck';
                }
            } elseif (isset($name) && empty($parentId) && count($this->mainRepository->getRow($id)) === 1) {
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
