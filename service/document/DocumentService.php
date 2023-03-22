<?php

namespace app\service\document;
use app\service\BaseService;
use app\service\Constants;
use app\service\main\MainService;
use Fpdf\Fpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;

class DocumentService extends BaseService
{

    private MainService $mainService;

    public function __construct()
    {
        $this->mainService = new MainService();
    }

    public function csvCreate(): void
    {
        $results = $this->mainService->getTree();
        header('Content-Disposition: attachment; filename="' . Constants::CSV_FILE_NAME . date('_d.m.Y_H:i:s') . '.csv' .'"');
        $outstream = fopen("php://output", "wb");
        fputcsv($outstream, array_keys($results[0]), ';');
        foreach($results as $result) {
            fputcsv($outstream, $result, ';');
        }
        fclose($outstream);
    }

    public function csvRead(): void
    {
        $result = [];
        $handle = fopen("C:\\Users\\Admin\\Downloads\\TreeCsv.csv", "r");
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
    function xlsxCreate(): void
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . Constants::XLSX_FILE_NAME . date('_d.m.Y_H:i:s') . '.xlsx' .'"');
        header('Cache-Control: max-age=0');
        $spreadsheet = new Spreadsheet();
        $result = $this->mainService->getTree();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(array_keys ($result[0]),NULL, 'A1');
        foreach ($result as $key=>$results) {
            $sheet->fromArray($results, NULL, 'A'.$key+2);
        }
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function pdfCreate(): void
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont(Constants::PDF_FONT, Constants::PDF_BOLD_FONT, Constants::PDF_SIZE_FONT);
        $result = $this->mainService->getTree();
        foreach ($result[0] as $key=>$value) {
            $pdf->Cell(40, 8, $key, 1, null, 'C');
        }
        $pdf->SetFont(Constants::PDF_FONT, null, 14);
        foreach ($result as $value) {
            $pdf->Ln();
            foreach ($value as $item)
            $pdf->Cell(40, 8, $item, 1, null, 'C');
        }
        $pdf->Output();
    }

    public function wordCreate(): void
    {
        $word = new PhpWord();
        $section = $word->addSection();
        $table = $section->addTable(Constants::WORD_TABLE_STYLE);
        $table->addRow();
        $output = $this->mainService->getTree();
        foreach ($output[0] as $key=>$value) {
            $cell = $table->addCell(2000);
            $cell->addText($key, Constants::WORD_CELL_TITLE_FONT, Constants::ALIGN_CENTER);
        }
        foreach ($output as $item) {
            $table->addRow();
            foreach ($item as $item2) {
                $cell = $table->addCell(2000);
                $cell->addText($item2, Constants::WORD_CELL_FONT, Constants::ALIGN_CENTER);
            }
        }
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . Constants::WORD_FILE_NAME . date('_d.m.Y_H:i:s') . '.docx' .'"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
        $objWriter->save('php://output');
    }
}