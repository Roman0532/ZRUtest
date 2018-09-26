<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvWriteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Сообщения для валидации
     *
     * @return array
     */
    public function messages()
    {
        return [
            'after-date.required' => 'Дата обязательна для заполнения',
            'after-date.date_format' => 'Неверный формат даты',
            'before-date.required' => 'Дата обязательна для заполнения',
            'before-date.date_format' => 'Неверный формат даты',
            'after-date.before' => 'Нельзя выбрать дату больше чем сейчас',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'after-date' => 'required|date_format:"Y-m-d H:00:00"|before:now',
            'before-date' => 'required|date_format:"Y-m-d H:00:00"|before:now',
        ];
    }
}
