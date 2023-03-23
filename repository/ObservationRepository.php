<?php

namespace app\repository;
use app\dbConnect;

class ObservationRepository
{
    private dbConnect $dbConnect;
    public function __construct()
    {
        $this->dbConnect = new dbConnect();
    }

    public function getAllObservations(): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getAllObservations.sql');
        return $this->dbConnect->query($sql);
    }
    public function getObservationById(int $id): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getObservationById.sql');
        $params = [[$id, 'int']];
        return $this->dbConnect->query($sql, $params);
    }

    public function getObservationByTreeId(int $treeId): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getObservationByTreeId.sql');
        $params = [[$treeId, 'int']];
        return $this->dbConnect->query($sql, $params);
    }
    public function getObservationByObserverId(int $observerId): array
    {
        $sql = file_get_contents(__DIR__ . '/sql/getObservationByObserverId.sql');
        $params = [[$observerId, 'int']];
        return $this->dbConnect->query($sql, $params);
    }
    public function insertObservation(int $treeId, int $observerId): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/insertObservation.sql');
        $params = [[$treeId, 'int'], [$observerId, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function deleteObservation(int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteObservation.sql');
        $params = [[$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateObservationTreeId(int $treeId, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateObservationTreeId.sql');
        $params = [[$treeId, 'int'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateObservationObserverId(int $observerId, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateObservationObserverId.sql');
        $params = [[$observerId, 'int'], [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function updateObservationAll(int $treeId, int $observerId, int $id): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/updateObservationAll.sql');
        $params = [[$treeId, 'int'], [$observerId, 'int'],  [$id, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function deleteObservationByTreeId (int $treeId): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteObservationByTreeId.sql');
        $params = [[$treeId, 'int']];
        $this->dbConnect->query($sql, $params);
    }

    public function deleteObservationByObserverId (int $observerId): void
    {
        $sql = file_get_contents(__DIR__ . '/sql/deleteObservationByObserverId.sql');
        $params = [[$observerId, 'int']];
        $this->dbConnect->query($sql, $params);
    }
}