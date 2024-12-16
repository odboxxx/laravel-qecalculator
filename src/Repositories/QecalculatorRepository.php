<?php

declare(strict_types=1);

namespace Odboxxx\LaravelQecalculator\Repositories;

use Illuminate\Support\Facades\DB;

class QecalculatorRepository
{
    /**
     * Извлечение истории решений из бд
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator Object
     */
    public static function historyGet(): object
    {
        return DB::table('qec_history')
                ->select(
                    DB::raw("
                        CAST(a as float) as a, 
                        CAST(b as float) as b, 
                        CAST(c as float) as c, 
                        CAST(x1 as float) as x1, 
                        CAST(x2 as float) as x2, 
                        roots_quant
                    ")
                )
                ->orderBy('id', 'desc')
                ->paginate(5);

    }

    /**
     * Добавление записи с новым решеним в бд
     * @param float $a
     * @param float $b
     * @param float $c
     * @param float $d
     * @param int $rootsQuant
     * @param float $x1
     * @param float $x2
     * 
     * @return void
     */      
    public  static function historySet(float $a, float $b, float $c, float $d, int $rootsQuant, float|null $x1, float|null $x2): void
    {
        $i['a'] = $a;
        $i['b'] = $b;
        $i['c'] = $c;
        $i['d'] = $d;
        $i['roots_quant'] = $rootsQuant;
        $i['date'] = date("Y-m-d H:i:s");
        $i['x1'] = $x1;
        $i['x2'] = $x2;

        DB::table('qec_history')->insert($i);
    }

    /**
     * Извлечение истории решений из бд для экспорта
     * 
     * @param int $status 0 - подлежит извлечению, *  1 - выгружался ранее
     * @param int $id строка начала выборки
     * @param int $limit
     * 
     * @return array of rows object
     */
    public static function historyGetForExport(int $status, int $id, int $limit): array
    { 
       
        return DB::table('qec_history')
            ->select(
                DB::raw("
                    id,
                    CAST(a as float) as a, 
                    CAST(b as float) as b, 
                    CAST(c as float) as c, 
                    CAST(d as float) as d, 
                    CAST(x1 as float) as x1, 
                    CAST(x2 as float) as x2, 
                    roots_quant,
                    date,
                    export_status
                ")
            )
            ->where('export_status', $status)
            ->where('id', '>', $id)
            ->limit($limit)
            ->orderBy('id')
            ->get()->toArray();

    }

    /**
     * Выборка id крайней записи в истории вычислений
     * 
     * @return int|null
     */
    public static function historyLastIdForExportGet(): int|null
    { 
        return DB::table('qec_history')
                ->select('id')
                ->where('export_status', 0)
                ->limit(1)
                ->orderBy('id','DESC')
                ->value('id');
    }   
    
    /**
     * Выборка количества вычислений по статусу
     * 
     * @param int $status
     * @return int
     */
    public static function historyNumberItemsByStatusGet(int $status): int
    { 
        return DB::table('qec_history')
                ->where('export_status', $status)
                ->count();
    }  

    /**
     * Обновление статуса экспортированных записей в истории вычислений
     * 
     * @param int $lastId
     * @param int $statusWhere
     * @param int $statusTo
     * 
     * @return int affected row
     */
    public static function historyStatusUpdate(int $lastId, int $statusWhere, int $statusTo): int
    { 
        return DB::table('qec_history')
                ->where('id', '<=', $lastId)
                ->where('export_status',  $statusWhere)
                ->update(['export_status' => $statusTo]);
    }     

    /**
     * Добавление записи в журнал экспорта
     * 
     * @param string $filepath
     * @param int $numRows
     * @param string $date
     * 
     * @return bool
     */      
    public  static function historyExportLogSet(string $filePath, int $numRows, string $date): bool
    {
        $i['file_path'] = $filePath;
        $i['num_rows'] = $numRows;
        $i['date'] = $date;

        return DB::table('qec_export_log')->insert($i);
    }    

    /**
     * Извлечение журнала экспорта истории вычислений
     * 
     * @return \Illuminate\Pagination\LengthAwarePaginator Object
     */
    public static function historyExportLogGet(): object
    {
        return DB::table('qec_export_log')
                ->select('id', 'file_path', 'num_rows', 'date')
                ->orderBy('id', 'DESC')
                ->paginate(10);

    }    

    /**
     * Извлечение записи из журнала экспорта по id
     * 
     * @param int $id
     * 
     * @return object|null
     */
    public static function historyExportLogGetById($id): object|null
    {
        return DB::table('qec_export_log')
                ->select('id', 'file_path', 'num_rows', 'date')
                ->where('id', $id)
                ->limit(1)
                ->first();

    }     
}
