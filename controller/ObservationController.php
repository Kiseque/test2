<?php

namespace app\controller;

use app\service\observation\ObservationService;

class ObservationController
{
    private ObservationService $observationService;
    public function __construct()
    {
        $this->observationService = new ObservationService();
    }

    public function getAllObservations(): array
    {
        return $this->observationService->getAllObservations();
    }

    public function getObservationById(int $id): array
    {
        return $this->observationService->getObservationById($id);
    }

    public function getObservationByTreeId(int $treeId): array
    {
        return $this->observationService->getObservationByTreeId($treeId);
    }

    public function getObservationByObserverId(int $observationId): array
    {
        return $this->observationService->getObservationByObserverId($observationId);
    }

    public function insertObservation(int $treeId, int $observerId): void
    {
        $this->observationService->insertObservation($treeId, $observerId);
    }

    public function deleteObservation(int $id): void
    {
        $this->observationService->deleteObservation($id);
    }

    public function updateObservation(? int $treeId, ? int $observerId, int $id): void
    {
        $this->observationService->updateObservation($treeId, $observerId, $id);
    }

}