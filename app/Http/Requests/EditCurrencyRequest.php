<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:5',
            'rate' => 'required|numeric'
        ];
    }
}
