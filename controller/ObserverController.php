<?php

namespace app\controller;

use app\service\observer\ObserverService;

class ObserverController
{
    private ObserverService $observerService;
    public function __construct()
    {
        $this->observerService = new ObserverService();
    }

    public function getAllObservers(): array
    {
        return $this->observerService->getAllObservers();
    }

    public function getObserver(int $id): array
    {
        return $this->observerService->getObserver($id);
    }

    public function insertObserver(string $name): void
    {
        $this->observerService->insertObserver($name);
    }

    public function deleteObserver(int $id): void
    {
        $this->observerService->deleteObserver($id);
    }

    public function updateObserver(string $name, int $id): void
    {
        $this->observerService->updateObserver($name, $id);
    }
}