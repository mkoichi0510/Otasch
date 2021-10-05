<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchedule extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'Text' => 'required|max:500',
            'priority' => 'required|max:2',
            'term' => 'required|date|after_or_equal:today',
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => '予定名',
            'Text' => '詳細',
            'priority' => '優先度',
            'term' => '期限'
        ];
    }
    public function messages()
    {
        return [
            'term.after_or_equal' => '今日以降の日付を入力してください。',
        ];
    }
}
