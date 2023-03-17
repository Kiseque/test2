<?php

namespace app\repository;

class DocumentRepository
{
    public function csvCreate($results)
    {
        header('Content-Disposition: attachment; filename=testcsv');
        $outstream = fopen("php://output", "wb");
        fputcsv($outstream, array_keys($results[0]), ';');
        foreach($results as $result) {
            fputcsv($outstream, $result, ';');
        }
        fclose($outstream);
    }
}