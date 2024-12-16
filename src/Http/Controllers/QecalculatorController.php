<?php

declare(strict_types=1);

namespace Odboxxx\LaravelQecalculator\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

use Odboxxx\LaravelQecalculator\Repositories\QecalculatorRepository;
use Odboxxx\LaravelQecalculator\Services\QecalculatorService;
use Odboxxx\LaravelQecalculator\Jobs\QecalculatorHistorySend;
use Odboxxx\LaravelQecalculator\Http\Requests\QecalculatorRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QecalculatorController
{
    public function __construct(
        protected QecalculatorService $calcService,
    ) {
    }

    /**
    * Cтраница с формой калькулятора
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\View\View
    */
    public function form(Request $request): View
    {
        $p = [];
        
        /*
            в случае ошибки валидации формы 
            или перенаправления после успешного решения уравнения,
            присваиваем переменным a,b,c ранее введенные значения
        */
        if (old('a') !== null) {
            $p['a'] = old('a');
        } elseif ($request->a) {
            $p['a'] = $request->a;
        } 
        if (old('b') !== null) {
            $p['b'] = old('b');
        } elseif ($request->b) {
            $p['b'] = $request->b;
        } 
        if (old('c') !== null) {
            $p['c'] = old('c');
        } elseif ($request->c) {
            $p['c'] = $request->c;
        }

        // если уравнение решено/не имеет решения передаем данные о вычислении в представление
        if ($request->data) {
            $p['data'] = $request->data;
        }

        return view('qecalculator::qecalculator.form', $p);
    }

    /**
    * Обработка запроса формы калькулятора методом post
    *
    * @param  \App\Http\Requests\QecalculatorRequest  $request
    * @return \Illuminate\Http\RedirectResponse
    */    
    public function post(QecalculatorRequest $request): RedirectResponse
    {
        // валидация формы
        $p = $request->all();

        // производим вычисления
        $p['data'] = $this->calcService->solutionGet($p['a'], $p['b'], $p['c']);

        // добавляем запись в журнал вычислений
        QecalculatorRepository::historySet(
            $p['a'], 
            $p['b'], 
            $p['c'], 
            $p['data']['d'], 
            $p['data']['roots'], 
            $p['data']['x1'], 
            $p['data']['x2']
        );

        return Redirect::route('qecalculator.form', $p);
    }

    /**
    * Обработка ajax запроса формы калькулятора методом post 
    *
    * @param  \App\Http\Requests\QecalculatorRequest  $request
    * @return \Illuminate\Http\JsonResponse
    */     
    public function ajax(QecalculatorRequest $request): JsonResponse
    {
        // валидация формы
        $p = $request->all();

        // производим вычисления
        $p['data'] = $this->calcService->solutionGet($p['a'], $p['b'], $p['c']);

        // добавляем запись в журнал вычислений
        QecalculatorRepository::historySet(
            $p['a'], 
            $p['b'], 
            $p['c'], 
            $p['data']['d'], 
            $p['data']['roots'], 
            $p['data']['x1'], 
            $p['data']['x2']
        );

        return response()->json($p);
    }

    /**
    * Страница истории вычислений
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\View\View
    */       
    public function history(Request $request): View
    {   
        // извлекаем данные истории вычислений из бд
        $p['list'] = QecalculatorRepository::historyGet();
    
        if ($p['list']->total() > 0) {
            // Запрос количества записей с результатами вычислений доступных для экспорта
            $p['new'] = QecalculatorRepository::historyNumberItemsByStatusGet(0);   
        }

        if ($request->res == 1) {
            $p['res'] = 1;
        }

        return view('qecalculator::qecalculator.history', $p);
    }

    /**
    * Экспорт новых вычислений в Excel и отправка на email администратора по запросу пользователя
    *   
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */       
    public function historyExport(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'format' => 'sometimes|numeric|min:1|max:2',
            'act' => 'required|numeric|min:1|max:2'
        ]);

        if (isset($validated['format'])) {
            $format = $validated['format'];
        } else {
            $format = 1;
        }

        QecalculatorHistorySend::dispatch($format);

        return Redirect::route('qecalculator.history', ['res' => 1]);        
    }  
    
    /**
    * Страница журнала экспорта истории вычислений
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\View\View
    */       
    public function historyExportLog(Request $request): View
    {   
        // извлекаем данные журнала
        $p['list'] = QecalculatorRepository::historyExportLogGet();

        return view('qecalculator::qecalculator.exportlog', $p);
    }  
    
      /**
    * Страница журнала экспорта истории вычислений
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Symfony\Component\HttpFoundation\StreamedResponse
    */       
    public function historyExportLogDownload(Request $request): StreamedResponse
    {   
        $validated = $request->validate([
            'id' => 'required|numeric|min:1',
        ]);

        $row = QecalculatorRepository::historyExportLogGetById($validated['id']);

        if (!empty($row)) {
            if (Storage::exists($row->file_path)) {
                return Storage::download($row->file_path);
            }
        }

        abort(404);
    }    
}
