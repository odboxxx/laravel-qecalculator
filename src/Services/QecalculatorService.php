<?php

declare(strict_types=1);

namespace Odboxxx\LaravelQecalculator\Services;

use Maatwebsite\Excel\Facades\Excel;

use Odboxxx\LaravelQecalculator\Exports\QecalculatorHistoryExport;
use Odboxxx\LaravelQecalculator\Repositories\QecalculatorRepository;

class QecalculatorService
{   
    /**
    * Вычисление дискриминанта
    *
    * @param float $a
    * @param float $b
    * @param float $c
    *
    * @return float значение дискриминанта
    */      
    public function discriminantGet(float $a, float $b, float $c): float
    {
        return pow($b, 2) - 4 * $a * $c;
    }

    /**
    * Решение уравнения
    *
    * @param float $a
    * @param float $b
    * @param float $c
    *
    * @return array [
    *   'roots' =>  int количество корней,
    *   'x1' => float|null,
    *   'x2' => float|null,
    *   'd' => float|null дискриминант
    * ]
    */  
    public function solutionGet(float $a, float $b, float $c): array
    {

        $d = $this->discriminantGet($a, $b, $c);

        if ($d < 0) {
            return ['roots' => 0, 'x1' => null, 'x2' => null, 'd' => $d];
        }
        if ($d == 0) {
            $x = (-1) * $b / (2 * $a);
            return ['roots' => 1, 'x1' => $x, 'x2' => null, 'd' => $d];
        }
        if ($d > 0) {
            $x1 = ((-1) * $b + sqrt($d)) / (2 * $a);
            $x2 = ((-1) * $b - sqrt($d)) / (2 * $a);
            return ['roots' => 2, 'x1' => $x1, 'x2' => $x2, 'd' => $d];
        }

    }

   /**
    * Экспорт истории вычислений
    *
    * @param int $format 
    *   1 - Excel,
    *   2 - Csv
    *
    * @return array ['rowsAffected' => int|false, 'filePath' => string]
    */  
    public static function historyExport(int $format = 1): array
    {
        $filePath = Config('qecalculator.file_prefix').date("Ymd_His");

        $lastId = QecalculatorRepository::historyLastIdForExportGet();

        if (empty($lastId)) {
            return [
                'rowsAffected' => 0
            ];
        }

        if ($format == 1) {

            $filePath .= '.xlsx';

            $exportResult = Excel::store(
                new QecalculatorHistoryExport($lastId), $filePath
            );
    
        } elseif ($format == 2){

            $filePath .= '.csv';
            
            $exportResult = Excel::store(
                new QecalculatorHistoryExport($lastId), 
                $filePath,
                \Maatwebsite\Excel\Excel::CSV
            );

        } else {

            throw new \Exception('Указан неизвестный формат для экспорта');

        }

        if ($exportResult) {

            $rowsAffected = QecalculatorRepository::historyStatusUpdate($lastId, 0, 1);

            $result = [
                'rowsAffected' => $rowsAffected,
                'filePath' => $filePath
            ];
            
        } else {
            $result = [
                'rowsAffected' => false,
            ];
        }

        return $result;
    }

   /**
    * Добавление записи в журнал экспорта
    *
    * @param string $filepath
    * @param int $numRows
    *
    * @return bool
    */  
    public static function historyExportLogSet(string $filePath, int $numRows): bool
    {
        $date = date("Y-m-d H:i:s");

        return QecalculatorRepository::historyExportLogSet($filePath, $numRows, $date);
    }    
}
