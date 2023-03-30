<?php

namespace app\service\observer;

use app\repository\ObserverRepository;
use app\service\BaseService;
use app\dbConnect;
use app\service\observation\ObservationService;

class ObserverService extends BaseService
{
    private ObserverRepository $observerRepository;
    private dbConnect $dbConnect;
    private ObservationService $observationService;
    public function __construct()
    {
        $this->observerRepository = new ObserverRepository();
        $this->dbConnect = new dbConnect();
        $this->observationService = new ObservationService();
    }

    public function getAllObservers(): array
    {
        return array_map('self::mb_ucfirst_arr', $this->observerRepository->getAllObservers());
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
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful observer insert";
    }

    public function deleteObserver(int $id): void
    {
        if (empty($this->getObserver($id))) {
            echo "Non-existing observer";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->observerRepository->deleteObserver($id);
            $this->observationService->deleteObservationByObserverId($id);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful observer delete";
    }

    public function updateObserver (string $name, int $id): void
    {
        if (empty($this->getObserver($id))) {
            echo "Non-existing observer";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->observerRepository->updateObserver($name, $id);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful observer update";
    }

}