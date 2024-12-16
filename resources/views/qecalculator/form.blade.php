@extends('qecalculator::layouts.qecalculator')

@section('title', 'Калькулятор для решения квадратного уравнения')

@section('content')

<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

<form method="POST" action="{{ route('qecalculator.post') }}" id="qec-form">
    @csrf

    <h1 class="mb-4 text-2xl font-extrabold text-gray-900 dark:text-white md:text-5xl lg:text-3xl text-center">Решение<br/><span class="text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">квадратного уравнения</span></h1>
    
    <!-- коэффициент a -->
    <div class="mt-10">
        <div class="flex">
        <div class="flex text-center justify-center items-center w-1/4">
            <x-qecalculator::input-label for="varA" value="a = " class="text-2xl" />
        </div>    
        <div class="flex w-3/4">
            <x-qecalculator::text-input id="varA" class="block mt-1 w-full" type="number" name="a" :value="@$a" required autofocus step="0.00001" />
        </div>
        </div>
        <div class="input-error w-4/4 text-end" id="error-a">
            @if (isset($errors))
            <x-qecalculator::input-error :messages="$errors->get('a')" class="mt-2" />
                @endif
        </div>        
    </div>  
    <!-- коэффициент b -->
    <div class="mt-2">       
        <div class="flex">
        <div class="flex text-center justify-center items-center w-1/4">
            <x-qecalculator::input-label for="varB" value="b = " class="text-2xl" />
        </div>   
        <div class="flex w-3/4">
            <x-qecalculator::text-input id="varB" class="block mt-1 w-full" type="number" name="b" :value="@$b" required step="0.00001" />
        </div>
        </div>
        <div class="input-error w-4/4 text-end" id="error-b">
            @if (isset($errors))
            <x-qecalculator::input-error :messages="$errors->get('b')" class="mt-2" />
            @endif
        </div>
    </div>   
    <!-- свободный член c -->
    <div class="mt-2">    
        <div class="flex"> 
        <div class="flex text-center justify-center items-center w-1/4">
            <x-qecalculator::input-label for="varC" value="c = " class="text-2xl" />
        </div>    
        <div class="flex w-3/4">
            <x-qecalculator::text-input id="varC" class="block mt-1 w-full" type="number" name="c" :value="@$c" required step="0.00001" />
        </div>
        </div> 
        <div class="input-error w-4/4 text-end" id="error-c">
            @if (isset($errors))
            <x-qecalculator::input-error :messages="$errors->get('c')" class="mt-2" />
            @endif
        </div>        
    </div>
    <!-- submit button -->
    <div class="flex justify-center items-center mt-10">   
    <x-qecalculator::primary-button class="ml-3" id="qec-submit">
        Найти решение
    </x-qecalculator::primary-button>
    </div>
    <!-- результат вычислений -->
    <div class="flex justify-center items-center text-center mt-10 text-orange-700" id="qec-response">
    @if (isset($data['roots']))
    @if ($data['roots']==0)
        Уравнение не имеет корней
    @elseif ($data['roots']==1)
        Уравнение имеет один корень<br />
        x = {{ $data['x1'] }}
    @else
        Уравнение имеет два корня<br />
        x1 = {{ $data['x1'] }}<br />
        x2 = {{ $data['x2'] }}
    @endif
    @endif
    </div>
    <!-- вывод ajax результата  -->
    <div class="flex justify-center items-center text-center mt-10 text-orange-700" id="qec-ajax-response" style="display: none">
    <p id="qec-ajax-message"></p>
    <p id="qec-ajax-x1"></p>
    <p id="qec-ajax-x2"></p>
    </div>
    <!-- ссылка на историю вычислений -->
    <div class="flex justify-end items-center mt-10">  
        <a href="{{ url(route('qecalculator.history')) }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
        История
        </a>
    </div>

</form>
</div>
</div>
@endsection

@section('js')
<script type="module">
window.onload = function()
{
if (window.$)
{
    $('#qec-response').css("display", "none");
    $(document).ready(function(){
        $(document).on('submit', '#qec-form', function(event) {
            event.preventDefault();
            $.ajax({
                url: '/qecalculator/ajax',
                method: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content'),
                },
                data: $(this).serialize(),
                success: function(rd){
                    $('.input-error').html('');
                    if (rd.data.roots === 0) {
                        $('#qec-ajax-message').text('Уравнение не имеет корней');
                    }
                    if (rd.data.roots === 1) {
                        $('#qec-ajax-message').text('Уравнение имеет один корень');
                        $('#qec-ajax-x1').text(rd.data.x1);
                    }
                    if (rd.data.roots === 2) {
                        $('#qec-ajax-message').text('Уравнение имеет два корня'); 
                        $('#qec-ajax-x1').text(rd.data.x1);
                        $('#qec-ajax-x2').text(rd.data.x2);
                    }
                    $('#qec-ajax-response').css("display", "block");
                },
                error: function(data){ 
                    let errors = data.responseJSON.errors;
					for (var ekey in errors) {
                        $('#error-'+ekey+'').html('');
						for (var fkey in errors[ekey]) {
							$('#error-'+ekey+'').append('<p class="text-sm text-red-600 space-y-1 mt-2">'+errors[ekey][fkey]+'</p>');
						}
					}
                }
            });
        });
    });
}
}
</script>
@endsection