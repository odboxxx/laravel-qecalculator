<?php

namespace Odboxxx\LaravelQecalculator\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Closure;

class QecalculatorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'a' => [
                'required',
                'numeric',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value == 0) {
                        $fail("Коэффициент {$attribute} не должен быть равным 0");
                    }
                }
            ],
            'b' => 'required|numeric',
            'c' => 'required|numeric',
        ];
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $this->replace([
            'a' => (float)$this->a,
            'b' => (float)$this->b,
            'c' => (float)$this->c,
        ]);
    }

    /**
     * Get the validation errors messages.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'a.required' => 'Укажите коэффициент a',
            'a.numeric' => 'Коэффициент a может принимать только числовое значение',
            'b.required' => 'Укажите коэффициент b',
            'b.numeric' => 'Коэффициент b может принимать только числовое значение',
            'c.required' => 'Укажите коэффициент c',
            'c.numeric' => 'Коэффициент b может принимать только числовое значение',
        ];
    }
}
