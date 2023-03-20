<?php

namespace app\repository;

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

    public function getRow($id)
    {
        $sql = file_get_contents(__DIR__ . '/sql/getRow.sql');
        $params = [[intval($id), 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function insertRow($name, $id)
    {
        $sql = file_get_contents(__DIR__ . '/sql/insertRow.sql');
        $params = [[strval($name), 'string'], [intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function getByParent($id)
    {
        $sql = file_get_contents(__DIR__ . '/sql/getByParent.sql');
        $params = [[intval($id), 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function deleteRow($id)
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteRow.sql');
        $params = [[intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateName($name, $id)
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateName.sql');
        $params = [[strval($name), 'string'], [intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateParent($parent_id, $id)
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateParent.sql');
        $params = [[intval($parent_id), 'int'], [intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateBoth($name, $parentId, $id) {
        $sql = file_get_contents(__DIR__ . '/sql/updateBoth.sql');
        $params = [[strval($name), 'string'], [intval($parentId), 'int'], [intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }

}
