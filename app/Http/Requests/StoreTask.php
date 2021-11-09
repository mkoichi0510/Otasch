<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTask extends FormRequest
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
        ];
    }
    
    public function attributes()
    {
        return [
            'name' => 'タスク名',
            'Text' => '詳細',
            'priority' => '優先度',
        ];
    }
}
