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
    public function getObserver(int $id): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getObserver.sql');
        $params = [[$id, 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function insertObserver(string $name): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/insertObserver.sql');
        $params = [[$name, 'string']];
        $this->dbConnect->query($sql, $params);
    }

    public function deleteObserver(int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteObserver.sql');
        $params = [[$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateObserver(string $name, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateObserver.sql');
        $params = [[$name, 'string'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }
}
