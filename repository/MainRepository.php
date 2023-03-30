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

    public function getTree(): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getTree.sql');
        return $this->dbConnect->query($sql);
    }

    public function getRow(int $id): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getRow.sql');
        $params = [[$id, 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function insertRow(string $name, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/insertRow.sql');
        $params = [[$name, 'string'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function getByParent(int $id): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getByParent.sql');
        $params = [[$id, 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function getByName (string $name): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getByName.sql');
        $params = [[$name, 'string']];
        return $this->dbConnect->query($sql, $params);
    }

    public function deleteRow(int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteRow.sql');
        $params = [[$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateName(string $name, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateName.sql');
        $params = [[$name, 'string'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateParent(int $parent_id, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateParent.sql');
        $params = [[$parent_id, 'int'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateBoth(string $name, int $parentId, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateBoth.sql');
        $params = [[$name, 'string'], [$parentId, 'int'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }
}
