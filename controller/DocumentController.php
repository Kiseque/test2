<?php

namespace app\controller;
use app\service\DocumentService;
class DocumentController
{
    private DocumentService $documentService;
    public function __construct()
    {
        $this->documentService = new DocumentService();
    }

    public function csvCreate()
    {
        $this->documentService->csvCreate();
    }

}