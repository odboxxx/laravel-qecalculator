<?php

namespace Odboxxx\LaravelQecalculator\Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;

use Maatwebsite\Excel\Facades\Excel;

use Odboxxx\LaravelQecalculator\Tests\TestCase;
use Odboxxx\LaravelQecalculator\Services\QecalculatorService;
use Odboxxx\LaravelQecalculator\Repositories\QecalculatorRepository;
use Odboxxx\LaravelQecalculator\Exports\QecalculatorHistoryExport;
use Odboxxx\LaravelQecalculator\Mail\QecalculatorHistoryMail;

class QecalculatorTest extends TestCase
{
    /**
     * Доступность формы
     */
    public function test_сalculator_form_page_returns_successful_response(): void
    {
        $response = $this->get('/qecalculator/form');

        $response->assertStatus(200);
        $response->assertSee('Калькулятор', $escaped = true);
    }
    /**
     * Ошибка валидации полей формы: а = 0 
     */
    public function test_form_field_a_validation_0_false(): void
    {
        $response = $this->post('/qecalculator/post', [
            'a' => 0, 
            'b' => 9,
            'c' => 10
        ]);
 
        $response->assertInvalid(['a']);
    }  
    /**
     * Ошибка валидации полей формы: одно из полей не заполнено
     */
    public function test_form_fields_validation_required_false(): void
    {
        $response = $this->post('/qecalculator/post', [
            'a' => '', 
            'b' => '',
            'c' => ''
        ]);
 
        $response->assertInvalid(['a','b','c']);
    }         
    /**
     * Ошибка валидации полей формы: введенные значения не являются вещественными числами 
     */    
    public function test_form_fields_validation_numeric_false(): void
    {
        $response = $this->post('/qecalculator/post', [
            'a' => 'h', 
            'b' => 'h',
            'c' => 'e'
        ]);
 
        $response->assertInvalid(['a','b','c']);
    } 
    /**
     * Поля формы заполнены корректно
     */
    public function test_form_fields_validation_true(): void
    {
        $response = $this->post('/qecalculator/post', [
            'a' => 1, 
            'b' => 5,
            'c' => 7
        ]);
 
        $response->assertValid(['a','b','c']);
    }       
    /**
     * Заполнение истории вычисленй
     */
    public function test_history_filling(): void
    {
        for($i = 0; $i < 1111; $i++) {
            QecalculatorRepository::historySet(rand(1,100),rand(1,100),rand(1,100),rand(1,100),rand(0,2),rand(1,100),rand(1,100));
        }

        $this->assertTrue(true);
    }        
    /**
     * Доступность истории
     */
    public function test_talculator_history_page_returns_a_successful_response(): void
    { 

        $this->test_history_filling();
        
        $response = $this->get('/qecalculator/history');

        $response->assertStatus(200);
    }  
    /**
     * Экспорт в Excel
     */
    public function test_can_store_history_export() 
    {

        Excel::fake();

        $filePath = config('qecalculator.file_prefix').date("Ymd_His").'.xlsx';

        Excel::store(
            new QecalculatorHistoryExport(), $filePath
        );

        Excel::assertStored($filePath);
        
        Excel::assertStored($filePath, function(QecalculatorHistoryExport $export) {
            return true;
        });
    }

    /**
     * Контроллер экспорта в Excel
     */
    public function test_export_controller() 
    {

        $response = $this->post('/qecalculator/history-export', [
            'format' => 1, 
            'act' => 1,
        ]);
 
        $response->assertValid();
        $response
        ->assertJson(fn (AssertableJson $json) =>
            $json->where('res', 1)
                 ->etc()
        );
    }

    /**
     * Содержимое письма с отчётом о вычислениях
     */        
    public function test_mailable_content(): void
    {

        $this->test_history_filling();

        $mailData = QecalculatorService::historyExport();

        $mailData['email'] = 'to@email.ru';

        $mailable = new QecalculatorHistoryMail($mailData);
     
        $mailable->assertFrom(config('qecalculator.email_from'));
        $mailable->assertTo($mailData['email']);
        $mailable->assertSeeInHtml('экспорта');

        if (isset($mailData['filePath'])) {
            $mailable->assertHasAttachment($mailData['filePath']);
        }
    }

}
