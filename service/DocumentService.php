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
        header('Content-Type: text/csv; charset = windows-1251');
        $outstream = fopen("php://output", "wb");
        fputcsv($outstream, array_keys($results[0]), ';');
        foreach($results as $result) {
            fputcsv($outstream, $result, ';');
        }
        fclose($outstream);
    }

    public function csvRead()
    {
        $result = [];
        $handle = fopen("C:\\Users\\yukia\\Downloads\\test.csv", "r");
        fgetcsv($handle, 0, ';');
        while (($raw_string = fgets($handle)) !== false) {
            $result[] = str_getcsv($raw_string, ';');
        }
        fclose($handle);
        foreach ($result as $results) {
            if(count($this->mainService->getRow($results[0])) === 1) {
                $this->mainService->updateRow($results[1], $results[2], $results[0]);
            }
            else {
                $this->mainService->insertRow($results[1], $results[0]);
            }
        }
    }
}