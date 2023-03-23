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
        $this->dbConnect->beginTransaction();
        try {
            if (!empty($this->mainRepository->getRow($treeId)) && !empty($this->observerRepository->getObserver($observerId))) {
                $this->observationRepository->insertObservation($treeId, $observerId);
                echo "Successful Observation insert";
            } else {
                echo "Error! Check TreeID or ObserverID";
            }
            $this->dbConnect->commit();
        } catch (\PDOException $e) {
            $this->dbConnect->rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteObservation(int $id): void
    {
        if (!empty($this->observationRepository->getObservationById($id))) {
            $this->dbConnect->beginTransaction();
            try {
                $this->observationRepository->deleteObservation($id);
                echo "Successful Observation delete";
                $this->dbConnect->commit();
            } catch (\PDOException $e) {
                $this->dbConnect->rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            echo "Non-existing observation";
        }
    }

    public function updateObservation (? int $treeId, ? int $observerId, int $id): void
    {
        $this->dbConnect->beginTransaction();
        if (!empty($this->observationRepository->getObservationById($id))) {
            try {
                if (isset($treeId) && !empty($this->mainRepository->getRow($treeId))) {
                    if (isset($observerId) && !empty($this->observerRepository->getObserver($observerId))) {
                        $this->observationRepository->updateObservationAll($treeId, $observerId, $id);
                        echo "Successful TreeID and ObserverID update";
                    } elseif (empty($observerId)) {
                        $this->observationRepository->updateObservationTreeId($treeId, $id);
                        echo "Successful TreeID update";
                    }
                } elseif (isset($observerId) && !empty($this->observerRepository->getObserver($observerId)) && empty($treeId)) {
                    $this->observationRepository->updateObservationObserverId($observerId, $id);
                    echo "Successful ObserverID update";
                } else {
                    echo "Error!";
                }
                $this->dbConnect->commit();
            } catch (\PDOException $e) {
                $this->dbConnect->rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            echo "Non-existing observation";
        }
    }

    public function deleteObservationByTreeId(int $treeId): void
    {
        if (!empty($this->observationRepository->getObservationByTreeId($treeId))) {
            $this->dbConnect->beginTransaction();
            try {
                $this->observationRepository->deleteObservationByTreeId($treeId);
                echo "Successful Observation delete";
                $this->dbConnect->commit();
            } catch (\PDOException $e) {
                $this->dbConnect->rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            echo "Non-existing observation";
        }
    }

    public function deleteObservationByObserverId(int $observerId): void
    {
        if (!empty($this->observationRepository->getObservationByObserverId($observerId))) {
            $this->dbConnect->beginTransaction();
            try {
                $this->observationRepository->deleteObservationByObserverId($observerId);
                echo "Successful Observation delete";
                $this->dbConnect->commit();
            } catch (\PDOException $e) {
                $this->dbConnect->rollBack();
                throw new \Exception($e->getMessage());
            }
        } else {
            echo "Non-existing observation";
        }
    }
}