<?php

namespace app\repository;

use app\dbConnect;

class ObserverRepository
{
    private dbConnect $dbConnect;
    public function __construct()
    {
        $this->dbConnect = new dbConnect();
    }

    public function getAllObservers(): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getAllObservers.sql');
        return $this->dbConnect->query($sql);
    }
    public function getObserver($id): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getObserver.sql');
        $params = [[intval($id), 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function insertObserver($name): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/insertObserver.sql');
        $params = [[strval($name), 'string']];
        $this->dbConnect->query($sql, $params);
    }

    public function deleteObserver($id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteObserver.sql');
        $params = [[intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateObserver($name, $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteObserver.sql');
        $params = [[strval($name), 'string'], [intval($id), 'int']];
        $this->dbConnect->query($sql, $params);
    }
}