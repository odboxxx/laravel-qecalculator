<?php

declare(strict_types=1);

namespace Odboxxx\LaravelQecalculator\Services;

use Closure;

use Odboxxx\LaravelQecalculator\Repositories\QecalculatorRepository;

class QecalculatorService
{   
    // валидация формы калькулятора
    public function formValidation($request): array
    {

        $validated = $request->validate([
            'a' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value == 0) {
                        $fail("Переменная {$attribute} не должна быть равной 0.");
                    }
                }
            ],
            'b' => 'required|numeric',
            'c' => 'required|numeric',
        ]);

        $p['a'] = (float)$validated['a'];
        $p['b'] = (float)$validated['b'];
        $p['c'] = (float)$validated['c'];

        return $p;

    }
    // вычисление дискриминанта
    public function discriminantGet(float $a, float $b, float $c): float
    {
        return pow($b, 2) - 4 * $a * $c;
    }
    // решение уравнения
    public function solutionGet(float $a, float $b, float $c): array
    {

        $D = $this->discriminantGet($a, $b, $c);

        if ($D < 0) {
            QecalculatorRepository::historySet($a, $b, $c, $D, 0, false, false);
            return ['roots' => 0];
        }
        if ($D == 0) {
            $x = (-1) * $b / (2 * $a);
            QecalculatorRepository::historySet($a, $b, $c, $D, 1, $x, false);
            return ['roots' => 1, 'x' => $x];
        }
        if ($D > 0) {
            $x1 = ((-1) * $b + sqrt($D)) / (2 * $a);
            $x2 = ((-1) * $b - sqrt($D)) / (2 * $a);
            QecalculatorRepository::historySet($a, $b, $c, $D, 2, $x1, $x2);
            return ['roots' => 2, 'x1' => $x1, 'x2' => $x2];
        }

    }
}
