<?php

declare(strict_types=1);

namespace Odboxxx\LaravelQecalculator\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class QecalculatorRepository
{
    // извлечение истории решений из бд
    public static function historyGet(): Object
    {
        $q = DB::table('qec_history')
            ->select(DB::raw("CAST(a as float) as a, CAST(b as float) as b, CAST(c as float) as c, CAST(x1 as float) as x1, CAST(x2 as float) as x2, roots_quant"))
            ->orderBy('id', 'desc')
            ->paginate(5);

        return $q;
    }
    // добавление записи с новым решеним в бд
    public  static function historySet($a, $b, $c, $d, $rootsQuant, $x1, $x2): void
    {
        $i['a'] = $a;
        $i['b'] = $b;
        $i['c'] = $c;
        $i['d'] = $d;
        $i['roots_quant'] = $rootsQuant;
        $i['date'] = date("Y-m-d H:i:s");

        if ($x1 !== false)
            $i['x1'] = $x1;
        if ($x2 !== false)
            $i['x2'] = $x2;

        DB::table('qec_history')->insert($i);
    }
}
