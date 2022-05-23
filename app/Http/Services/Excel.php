<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel {

    public static function generate($query)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $data = [];

        foreach ($query as $record) {
            if (!$data) {
                array_push( $data, array_keys((array) $record) );
            }
            array_push( $data, array_values((array) $record) );
        }

        $sheet->fromArray(
            $data,
            null,
            'A1'
        );

        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(TRUE);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($path = storage_path('report.xlsx'));
        return $path;
    }

    public static function nextColumn($location) {
        $onlyLetters = preg_replace("/[^a-zA-Z]+/", "", $location);
        $onlyNumbers = preg_replace("/[^0-9]+/", "", $location);
        $ch = $onlyLetters;
        $next_ch = ++$ch;
        return $next_ch . $onlyNumbers;
    }

    public static function nextRow($location) {
        $onlyLetters = preg_replace("/[^a-zA-Z]+/", "", $location);
        $onlyNumbers = preg_replace("/[^0-9]+/", "", $location);
        $numbers = (int) ($onlyNumbers);
        $next_numbers = ++$numbers;
        return $next_numbers . $onlyLetters;
    }

}