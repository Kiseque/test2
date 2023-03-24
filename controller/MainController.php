<?php

namespace app\controller;

use app\service\main\MainService;

class MainController
{
    private MainService $mainService;
    public function __construct()
    {
        $this->mainService = new MainService();
    }

    public function getTree(): array
    {
        return $this->mainService->getTree();
    }

    public function getRow(int $id): array
    {
        return $this->mainService->getRow($id);
    }

    public function insertRow(string $name, int $id): void
    {
        $this->mainService->insertRow($name, $id);
    }

    public function deleteRow(int $id): void
    {
        $this->mainService->deleteRow($id);
    }

    public function updateRow(?string $name, ?int $parent_id, int $id): void
    {
        $this->mainService->updateRow($name, $parent_id, $id);
    }

    public function displayTree(): void
    {
        $this->mainService->displayTree();
    }

}