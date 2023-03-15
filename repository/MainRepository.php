<?php

namespace app\repository;

use app\controller\MainController;
use app\dbConnect;

class MainRepository
{
    private dbConnect $dbConnect;

    public function __construct()
    {
        $this->dbConnect = new dbConnect();
    }

    public function getTree()
    {
        $sql = file_get_contents(__DIR__ . '/sql/getTree.sql');
        return $this->dbConnect->query($sql);
    }

}