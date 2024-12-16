@extends('qecalculator::layouts.qecalculator')

@section('title', 'Журнал экспорта решений квадратного уравнения')

@section('content')
 
<div class="min-h-screen flex flex-col sm:justify-top items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-90 m-5 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

<h1 class="mb-4 text-2xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-3xl text-center">Журнал экспорта</h1>

@if (count($list)>0)
<!-- таблица результатов -->
<table class="table-auto">
    <thead>
      <tr>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Кол-во записей</th>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Дата</th>
        <th class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">Ссылка на файл</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($list as $row)
      <tr>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            {{ $row->num_rows }}
        </td>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            {{ $row->date }}
        </td>
        <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400 text-center">
            <a href="{{ url(route('qecalculator.history.exportlog.download',['id'=>$row->id])) }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                Скачать
            </a>
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
Нет данных
@endif
    <!-- ссылка на калькулятор -->
    <div class="flex justify-between items-center mt-10"> 
        <a href="{{ url(route('qecalculator.history')) }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
        История вычислений
        </a>
    </div>

</div>
</div>

@endsection
