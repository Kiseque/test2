<?php

namespace app\service\observation;

use app\dbConnect;
use app\repository\MainRepository;
use app\repository\ObservationRepository;
use app\repository\ObserverRepository;
use app\service\BaseService;

class ObservationService extends BaseService
{
    private ObservationRepository $observationRepository;
    private ObserverRepository $observerRepository;
    private MainRepository $mainRepository;
    private dbConnect $dbConnect;

    public function __construct()
    {
        $this->dbConnect = new dbConnect();
        $this->observationRepository = new ObservationRepository();
        $this->observerRepository = new ObserverRepository();
        $this->mainRepository = new MainRepository();
    }

    public function getAllObservations(): array
    {
        return $this->observationRepository->getAllObservations();
    }

    public function getObservationById(int $id): array
    {
        return $this->observationRepository->getObservationById($id);
    }

    public function getObservationByTreeId(int $treeId): array
    {
        return $this->observationRepository->getObservationByTreeId($treeId);
    }

    public function getObservationByObserverId(int $observationId): array
    {
        return $this->observationRepository->getObservationByObserverId($observationId);
    }

    public function insertObservation(int $treeId, int $observerId): void
    {
        if (empty($this->mainRepository->getRow($treeId)) || empty($this->observerRepository->getObserver($observerId))) {
            echo "Error! Check TreeID or ObserverID";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->observationRepository->insertObservation($treeId, $observerId);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful Observation insert";
    }

    public function deleteObservation(int $id): void
    {
        if (empty($this->observationRepository->getObservationById($id))) {
            echo "Non-existing observation";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->observationRepository->deleteObservation($id);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful Observation delete";
    }

    public function updateObservation (?int $treeId, ?int $observerId, int $id): void
    {
        if (empty($this->getObservationById($id))) {
            echo "Non-existing observation";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            if (isset($treeId)) {
                $check1 = $this->checkTreeIdExistence($treeId);
            }
            if (isset($observerId)) {
                $check2 = $this->checkObserverIdExistence($observerId);
            }
            if (isset($treeId, $observerId)) {
                if ($check1 && $check2) {
                    $this->observationRepository->updateObservationAll($treeId, $observerId, $id);
                } else {
                    throw new \Exception('Error!');
                }
            } elseif (isset($treeId)) {
                if ($check1) {
                    $this->observationRepository->updateObservationTreeId($treeId, $id);
                }
            } elseif (isset($observerId)) {
                if ($check2) {
                    $this->observationRepository->updateObservationObserverId($observerId, $id);
                }
            } else {
                echo "Проверьте правильность ввода параметров";
            }
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Успешное изменение записи";
    }

    public function deleteObservationByTreeId(int $treeId): void
    {
        if (empty($this->observationRepository->getObservationByTreeId($treeId))) {
            echo "Non-existing observation";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->observationRepository->deleteObservationByTreeId($treeId);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful Observation delete";
    }

    public function deleteObservationByObserverId(int $observerId): void
    {
        if (empty($this->observationRepository->getObservationByTreeId($observerId))) {
            echo "Non-existing observation";
            exit;
        }
        $this->dbConnect->beginTransaction();
        try {
            $this->observationRepository->deleteObservationByObserverId($observerId);
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
        echo "Successful Observation delete";
    }

    private function checkTreeIdExistence (int $treeId): bool
    {
        return !empty($this->mainRepository->getRow($treeId));
    }

    private function checkObserverIdExistence (int $observerId): bool
    {
        return !empty($this->observerRepository->getObserver($observerId));
    }

}