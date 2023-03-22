<?php

namespace app\controller;

use app\service\document\DocumentService;

class ObserverController
{
    private DocumentService $documentService;
    public function __construct()
    {
        $this->documentService = new DocumentService();
    }

    public function getAllObservers(): array
    {
        return $this->documentService->getAllObservers();
    }

    public function getObserver(int $id): array
    {
        return $this->documentService->getObserver($id);
    }

    public function insertObserver(string $name): void
    {
        $this->documentService->insertObserver($name);
    }

    public function deleteObserver(int $id): void
    {
        $this->documentService->deleteObserver($id);
    }

    public function updateObserver(string $name, int $id): void
    {
        $this->documentService->updateObserver($name, $id);
    }
}