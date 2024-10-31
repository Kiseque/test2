<?php

namespace app\controller;

use app\service\document\DocumentService;

class DocumentController
{
    private DocumentService $documentService;
    public function __construct()
    {
        $this->documentService = new DocumentService();
    }

    public function csvCreate(): void
    {
        $this->documentService->csvCreate();
    }

    public function csvRead(): void
    {
        $this->documentService->csvRead();
    }

    public function xlsxCreate(): void
    {
        $this->documentService->xlsxCreate();
    }

    public function pdfCreate(): void
    {
        $this->documentService->pdfCreate();
    }

    public function wordCreate(): void
    {
        $this->documentService->wordCreate();
    }

    public function pdfCreateObservation(): void
    {
        $this->documentService->pdfCreateObservation();
    }

    public function wordCreateObservation(): void
    {
        $this->documentService->wordCreateObservation();
    }
}
