<?php

namespace Odboxxx\LaravelQecalculator\Tests\Feature;

use Odboxxx\LaravelQecalculator\Tests\TestCase;

class QecalculatorTest extends TestCase
{
    /**
     * Доступность формы
     */
    public function test_the_сalculator_form_page_returns_successful_response(): void
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/qecalculator/form');

        $response->assertStatus(200);
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
     * Доступность истории
     */
    public function test_the_сalculator_history_page_returns_a_successful_response(): void
    {
        $response = $this->get('/qecalculator/form');

        $response->assertStatus(200);
    }           
}
