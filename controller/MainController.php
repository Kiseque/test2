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
}