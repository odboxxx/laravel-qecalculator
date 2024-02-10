<?php

declare(strict_types=1);

namespace Odboxxx\LaravelQecalculator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;

use Odboxxx\LaravelQecalculator\Repositories\QecalculatorRepository;
use Odboxxx\LaravelQecalculator\Services\QecalculatorService;

class QecalculatorController
{
    public function __construct(
        protected QecalculatorService $calcService,
    ) {
    }
    // страница с формой калькулятора
    public function form(Request $request): View
    {
        $p = [];
        
        /*
            в случае ошибки валидации формы 
            или перенаправления после успешного решения уравнения,
            присваиваем переменным a,b,c ранее введенные значения
        */
        if (old('a') !== null) $p['a'] = old('a');
        elseif ($request->a) $p['a'] = $request->a;
        if (old('b') !== null) $p['b'] = old('b');
        elseif ($request->b) $p['b'] = $request->b;
        if (old('c') !== null) $p['c'] = old('c');
        elseif ($request->c) $p['c'] = $request->c;

        // если уравнение решено/не имеет решения передаем данные о вычислении в представление
        if ($request->data) {
            $p['data'] = $request->data;
        }

        return view('qecalculator::qecalculator.form', $p);
    }
    // обработка запроса формы калькулятора методом post
    public function post(Request $request): RedirectResponse
    {
        // валидация формы
        $p = $this->calcService->formValidation($request);
        // производим вычисления
        $p['data'] = $this->calcService->solutionGet($p['a'], $p['b'], $p['c']);

        return Redirect::route('qecalculator.form', $p);
    }
    // обработка ajax запроса формы калькулятора методом post 
    public function ajax(Request $request): JsonResponse
    {
        // валидация формы
        $validated = $this->calcService->formValidation($request);
        // производим вычисления
        $p = $this->calcService->solutionGet($validated['a'], $validated['b'], $validated['c']);

        return response()->json($p);
    }
    // страница истории вычислений
    public function history(Request $request): View
    {
        // извлекаем данные истории вычислений из бд
        $p['list'] = QecalculatorRepository::historyGet();

        return view('qecalculator::qecalculator.history', $p);
    }
}
