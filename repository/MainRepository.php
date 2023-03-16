<?php

namespace app\repository;

use app\controller\MainController;
use app\dbConnect;
$counter = 0;
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

    public function updateBoth($name, $parent_id, $id) {
        $sql = file_get_contents(__DIR__ . '/sql/updateBoth.sql');
        $params = [[strval($name), 'string'], [intval($parent_id), 'int'], [intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }
    public function updateRow($column, $value, $id)
    {
        $sql = str_replace('{{column}}', $column, file_get_contents(__DIR__ . '/sql/updateName.sql'));
        if ($column === 'name' && count($this->getRow($id)) === 1) {
            $params = [[strval($value), 'string'], [intval($id), 'int']];
            $this->dbConnect->query($sql, $params);
            echo "Переименование прошло успешно";
        } else if ($column === 'parent_id' && count(getRow($id)) === 1 && $value !== $id && (count(getRow($value)) === 1 || $value == 0) || $value == null) {
            $temp = array_column(getRow($id), 'parent_id');
            $params = [[strval($value), 'int'], [intval($id), 'int']];
            $this->dbConnect->query($sql, $params);
            if(countElems('1') == count(selectTree())) {                   //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
                echo "Успешно";
            } else {
                echo "Произошла ошибка";
                $params = [[$temp[0], 'int'], [intval($id), 'int']];
                $this->dbConnect->query($sql, $params);
            }
        } else {
            echo "Error";
        }
    }


}