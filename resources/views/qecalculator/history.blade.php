@extends('qecalculator::layouts.qecalculator')

@section('title', 'История решений квадратного уравнения')

@section('content')
 
<div class="min-h-screen flex flex-col sm:justify-top items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-90 m-5 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

<h1 class="mb-4 text-2xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-3xl text-center">История вычислений</h1>

@if (count($list)>0)
<!-- таблица результатов -->
<table class="table-auto">
    <thead>
      <tr>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Значение <span class="text-orange-700">а</span></th>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Значение <span class="text-orange-700">b</span></th>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Значение <span class="text-orange-700">c</span></th>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Результат</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($list as $row)
      <tr>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            {{ $row->a }}
        </td>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            {{ $row->b }}
        </td>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            {{ $row->c }}
        </td>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            @if ($row->roots_quant === 0)
            Нет решения
            @elseif ($row->roots_quant === 1)
            {{ $row->x1 }}
            @else
            {{ $row->x1 }}, {{ $row->x2 }}
            @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

<!-- нумерация страниц -->
<div class="mt-10">
{{ $list->onEachSide(0)->links('qecalculator::qecalculator.pagination') }}
<div>
@else
Нет данных о ранее выполненных вычислениях
@endif
    <!-- ссылка на калькулятор -->
    <div class="flex justify-between items-center mt-10"> 
        @if (empty($res) && isset($new)) 
        <form method="post" action="{{ route('qecalculator.historyex') }}" id="he-form">
          @csrf
          <input type="hidden" name="format" value="1" />
          <input type="hidden" name="act" value="1" />
          <x-qecalculator::primary-button class="ml-3" id="he-submit">
            Экспорт
          </x-qecalculator::primary-button>
        </form>
        @endif
        <a href="{{ url(route('qecalculator.history.exportlog')) }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
          Журнал экспорта
          </a>        
        <a href="{{ url(route('qecalculator.form')) }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
        Калькулятор
        </a>
    </div>

</div>
</div>

@endsection
