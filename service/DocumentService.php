<?php

namespace app\service;


class DocumentService
{

    private MainService $mainService;

    public function __construct()
    {
        $this->mainService = new MainService();
    }

    public function csvCreate()
    {
        $results = $this->mainService->getTree();
        header('Content-Disposition: attachment; filename=test.csv');
        $outstream = fopen("php://output", "wb");
        fputcsv($outstream, array_keys($results[0]), ';');
        foreach($results as $result) {
            fputcsv($outstream, $result, ';');
        }
        fclose($outstream);
    }
}