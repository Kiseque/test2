<?php

namespace app\service\observer;

use app\repository\ObserverRepository;
use app\service\BaseService;
use app\dbConnect;
class ObserverService extends BaseService
{
    private ObserverRepository $observerRepository;
    private dbConnect $dbConnect;

    public function __construct()
    {
        $this->observerRepository = new ObserverRepository();
        $this->dbConnect = new dbConnect();
    }

    public function getAllObservers(): array
    {
        return array_map('self::upperCaseName', $this->observerRepository->getAllObservers());
    }

    public function getObserver(int $id): array
    {
        return $this->observerRepository->getObserver($id);
    }

    public function insertObserver(string $name): void
    {
        $this->dbConnect->beginTransaction();
        try {
            $this->observerRepository->insertObserver($name);
            $this->dbConnect->commit();
            echo "Successful observer insert";
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteObserver(int $id): void
    {
        $this->dbConnect->beginTransaction();
        try {
            if (!empty($this->getObserver($id))) {
                $this->observerRepository->deleteObserver($id);
                echo "Successful observer delete";
            } else {
                echo "Non-existing observer";
            }
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function updateObserver (string $name, int $id): void
    {
        $this->dbConnect->beginTransaction();
        try {
            if (!empty($this->getObserver($id))) {
                $this->observerRepository->updateObserver($name, $id);
            } else {
                echo "Non-existing observer";
            }
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    private function upperCaseName(array $row)
    {
        $row['Name'] = $this->mb_ucfirst($row['Name']);
        return $row;
    }
}