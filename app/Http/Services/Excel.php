<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
            $values = array_values((array) $record);
            $rendered_values = [];
            foreach ($values as $value) {
                try {
                    array_push($rendered_values, strip_tags($value));
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            array_push( $data,  $rendered_values);
        }

        $sheet->fromArray(
            $data,
            null,
            'A1'
        );
        $lasColumn = 'A'; 
        for ($i = 'A'; $i !=  $spreadsheet->getActiveSheet()->getHighestColumn(); $i++) {
            $spreadsheet->getActiveSheet()->getColumnDimension($i)->setWidth(30.0);
            $lasColumn = $i;
            for ($j = 1; $j <=  $spreadsheet->getActiveSheet()->getHighestRow(); $j++) {
                $text = $spreadsheet->getActiveSheet()->getCell($i.$j)->getValue();
                $height = ceil(strlen($text) / 50) * 20;
                if ($height < $spreadsheet->getActiveSheet()->getRowDimension($j)->getRowHeight()) { $height = $spreadsheet->getActiveSheet()->getRowDimension($j)->getRowHeight() ; }
                $spreadsheet->getActiveSheet()->getRowDimension($j)->setRowHeight($height);
                $spreadsheet->getActiveSheet()->getStyle($i.$j)->applyFromArray(
                    [
                        'alignment' => [
                            'horizontal' => $height <= 20 ? Alignment::HORIZONTAL_CENTER : Alignment::HORIZONTAL_JUSTIFY,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'quotePrefix'    => true
                    ]
                );
            }
        }
        $lasColumn++;
        $spreadsheet->getActiveSheet()->getColumnDimension($lasColumn)->setWidth(30.0);

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