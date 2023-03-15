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
}