<?php

namespace app\service;

use app\repository\MainRepository;

class MainService
{
    private MainRepository $mainRepository;

    public function __construct()
    {
        $this->mainRepository = new MainRepository();
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
        if (count($this->mainRepository->getRow($id)) === 1) {
            $this->mainRepository->insertRow($name, $id);
        }
    }

    public function deleteRow($id)
    {
        $children = array_column($this->mainRepository->getByParent($id), 'ID');
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->deleteRow($child);
            }
        }
        $this->mainRepository->deleteRow($id);
    }

    public function countElems($id)
    {
        static $counter = 0;
        if ($counter > count($this->mainRepository->getTree())) {
            return $counter;
        }
        $counter++;
        $children = array_column($this->mainRepository->getByParent($id), 'ID');
        if (!empty($children)) {
            foreach ($children as $child) {
                $this->countElems($child);
            }
        }
        return $counter;
    }

    public function updateRow($name, $parent_id, $id)
    {
        if (isset($name) && isset($parent_id) && count($this->mainRepository->getRow($id)) === 1 && $parent_id !== $id && (count($this->mainRepository->getRow($parent_id)) === 1 || $parent_id == 0 || $parent_id == null)) {
            $temp = array_column($this->mainRepository->getRow($id), 'Parent_ID');
            $this->mainRepository->updateBoth($name, $parent_id, $id);
            if ($this->countElems(1) === count($this->mainRepository->getTree())) {
                echo "Successfull";
            } else {
                $this->mainRepository->updateParent($temp[0], $id);
            }
        } elseif (isset($parent_id) && empty($name) && count($this->mainRepository->getRow($id)) === 1 && $parent_id !== $id && (count($this->mainRepository->getRow($parent_id)) === 1 || $parent_id == 0 || $parent_id == null)) {
            $temp = array_column($this->mainRepository->getRow($id), 'Parent_ID');
            $this->mainRepository->updateParent($parent_id, $id);
            if ($this->countElems(1) === count($this->mainRepository->getTree())) {
                echo "Successfull";
            } else {
                $this->mainRepository->updateParent($temp[0], $id);
            }
        } elseif (isset($name) && empty($parent_id) && count($this->mainRepository->getRow($id)) === 1) {
            $this->mainRepository->updateName($name, $id);
        }
    }
}