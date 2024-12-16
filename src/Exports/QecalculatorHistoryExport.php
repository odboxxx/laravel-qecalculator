<?php

namespace Odboxxx\LaravelQecalculator\Exports;

use Generator;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

use Odboxxx\LaravelQecalculator\Repositories\QecalculatorRepository;

class QecalculatorHistoryExport implements FromGenerator, WithHeadings, WithTitle
{
    use Exportable;

    public function generator(): Generator
    {

        $limit = 1000;
        $lastId = 0;
        do {
            $previousId = $lastId;
            $items = QecalculatorRepository::historyGetForExport(0, $lastId, $limit);
            foreach ($items as $item) {
                $lastId = $item->id;
                yield [
                    $item->id, 
                    (float)$item->a, 
                    (float)$item->b, 
                    (float)$item->c, 
                    (float)$item->d, 
                    (float)$item->x1, 
                    (float)$item->x2, 
                    (int)$item->roots_quant, 
                    date("H:i:s d.m.Y",strtotime($item->date))
                ];
            }
        } while ($lastId > $previousId);

    }

    /**
     * @return array
     */
    public function headings(): array
    {

        $r[] = 'id';
        $r[] = 'a';
        $r[] = 'b';
        $r[] = 'c';
        $r[] = 'd';
        $r[] = 'x1';
        $r[] = 'x2';
        $r[] = 'Кол-во корней';
        $r[] = 'Дата вычисления';

        return $r;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Журнал вычислений';
    }        
}
