<?php

namespace app\service;
use app\repository\DocumentRepository;
use app\repository\MainRepository;

class DocumentService
{
    private DocumentRepository $documentRepository;
    private MainService $mainService;

    public function __construct()
    {
        $this->documentRepository = new DocumentRepository();
        $this->mainService = new MainService();
    }

    public function csvCreate()
    {
        $results = $this->mainService->getTree();
        $this->documentRepository->csvCreate($results);
    }
}