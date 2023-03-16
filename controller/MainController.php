<?php

namespace app\controller;

use app\service\MainService;

class MainController
{
    private MainService $mainService;
    public function __construct()
    {
        $this->mainService = new MainService();
    }

    public function getTree()
    {
        return $this->mainService->getTree();
    }

    public function getRow($id)
    {
        return $this->mainService->getRow($id);
    }

    public function insertRow($name, $id)
    {
        $this->mainService->insertRow($name, $id);
    }

    public function deleteRow($id)
    {
        $this->mainService->deleteRow($id);
    }

    public function updateRow($name, $parent_id, $id)
    {
        $this->mainService->updateRow($name, $parent_id, $id);
    }
}