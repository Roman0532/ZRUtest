<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
     * Получить сообщения дла валидации
     *
     * @return array
     */
    public function messages()
    {
        return [
            'amount.required' => 'Сумма обязательна для заполнения',
            'amount.numeric' => 'Сумма должна быть числом',
            'amount.max' => 'Сумма не должна превышать 1000000',
            'amount.min' => 'Сумма платежа должна быть больше 0',
            'fromUser.required' => 'Поле от кого обязательно для заполнения',
            'toUser.required' => 'Поле кому кого обязательно для заполнения',
            'date.date_format' => 'Неправильный формат даты',
            'fromUser.different' => 'Невозможно перевести денежные средства самому себе',
            'date.after' => 'Нельзя запланировать транзакцию на прошедшее время',
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
            'amount' => 'required|numeric|min:0.5|max:1000000',
            'fromUser' => 'required|exists:users,id|different:toUser',
            'toUser' => 'required|exists:users,id',
            'date' => 'required|date_format:"Y-m-d H:00:00"|after:now',
        ];
    }
}
