<?php

namespace app\service\document;

use app\service\BaseService;
use app\service\Constants;
use app\service\main\MainService;
use app\service\observation\ObservationService;
use app\service\observer\ObserverService;
use Fpdf\Fpdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Reader\Word2007;

class DocumentService extends BaseService
{
    private MainService $mainService;
    private ObservationService $observationService;
    private ObserverService $observerService;

    public function __construct()
    {
        $this->mainService = new MainService();
        $this->observationService = new ObservationService();
        $this->observerService = new ObserverService();
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
    public function xlsxCreate(): void
    {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . Constants::XLSX_FILE_NAME . date('_d.m.Y_H:i:s') . '.xlsx' .'"');
        header('Cache-Control: max-age=0');
        $spreadsheet = new Spreadsheet();
        $result = $this->mainService->getTree();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray(array_keys($result[0]),null, 'A1');
        foreach ($result as $key => $results) {
            $sheet->fromArray($results, null, 'A'. $key + 2);
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
        foreach ($result[0] as $key => $value) {
            $pdf->Cell(40, 8, $key, 1, null, 'C');
        }
        $pdf->Cell(40, 8, 'Parent Name', 1, null, 'C');
        $pdf->SetFont(Constants::PDF_FONT, null, 14);
        foreach ($result as $value) {
            $pdf->Ln();
            foreach ($value as $key2 => $item) {
                $pdf->Cell(40, 8, $item, 1, null, 'C');
                if ($key2 === 'Parent_ID') {
                    $name = $this->parentNameByParentID($item);
                    $pdf->Cell(40, 8, $name, 1, null, 'C');
                }
            }
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
        foreach ($output[0] as $key => $value) {
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
        header('Content-Disposition: attachment; filename="' . Constants::WORD_FILE_NAME . date('_d.m.Y_H:i:s') . '.docx' .'"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
        $objWriter->save('php://output');
    }

    public function pdfCreateObservation(): void
    {
        $pdf = new Fpdf();
        $pdf->AddPage();
        $pdf->SetFont(Constants::PDF_FONT, Constants::PDF_BOLD_FONT, Constants::PDF_SIZE_FONT);
        $titles = ['ID', 'TreeName', 'ObserverName'];
        foreach ($titles as $item) {
            $pdf->Cell(40, 8, $item, 1, null, 'C');
        }
        $result = array_map('self::idReplaceWithNames', $this->observationService->getAllObservations());
        $pdf->SetFont(Constants::PDF_FONT, null, 14);
        foreach ($result as $value) {
            $pdf->Ln();
            foreach ($value as $item)
                $pdf->Cell(40, 8, $item, 1, null, 'C');
        }
        $pdf->Output();
    }

    private function idReplaceWithNames (array $result): array
    {
        $tree = array_column($this->mainService->getTree(), 'Name', 'ID');
        $observer = array_column($this->observerService->getAllObservers(), 'Name', 'ID');
        $result['ObserverID'] = $observer[$result['ObserverID']];
        $result['TreeID'] = $tree[$result['TreeID']];
        return $result;
    }

    public function wordCreateObservation(): void
    {
        $word = new PhpWord();
        $section = $word->addSection();
        $table = $section->addTable(Constants::WORD_TABLE_STYLE);
        $table->addRow();
        $output = array_map('self::idReplaceWithNames', $this->observationService->getAllObservations());
        $titles = ['ID', 'TreeName', 'ObserverName'];
        foreach ($titles as $item)
        {
            $cell = $table->addCell(2000);
            $cell->addText($item, Constants::WORD_CELL_FONT, Constants::ALIGN_CENTER);
        }
        foreach ($output as $value) {
            $table->addRow();
            foreach ($value as $key => $value2) {
                $cell = $table->addCell(2000);
                if ($key == "TreeID" && $this->noParentsCheck($value2)) {
                    $footnote = $cell->addFootnote();
                    $footnote->addText('Вершина');
                }
                $cell->addText($value2, Constants::WORD_CELL_FONT, Constants::ALIGN_CENTER);
            }
        }
        header('Content-Disposition: attachment; filename="' . Constants::WORD_FILE_NAME_OBS . date('_d.m.Y_H:i:s') . '.docx' .'"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($word, 'Word2007');
        $objWriter->save('php://output');
    }

    private function noParentsCheck (string $name): bool
    {
        $temp = $this->mainService->getByName($name);
        return $temp[0]['Parent_ID'] == 0;
    }

    private function parentNameByParentID (int $id): string
    {
        $parent = $this->mainService->getRow($id);
        return !empty($parent) ? $parent[0]['Name'] : 'No parent';
    }
}
