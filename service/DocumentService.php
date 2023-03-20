<?php

namespace app\service;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function csvRead()
    {
        $result = [];
        $handle = fopen("C:\\Users\\Admin\\Downloads\\test.csv", "r");
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
    function xlsxCreate()
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="myfile.xlsx"');
        header('Cache-Control: max-age=0');
        $spreadsheet = new Spreadsheet();
        $result = $this->mainService->getTree();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(array_keys ($result[0]),NULL, 'A1');
        $i = 2;
        foreach ($result as $results) {
            $sheet->fromArray($results, NULL, 'A'.$i);
            $i++;
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}