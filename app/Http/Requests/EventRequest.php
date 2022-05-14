<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        // dd($this->all());
        return [
            'title' => 'required|max:255',
            'description' => 'string',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'timezone' => 'required|string',
            'modalName' => 'required|string',
            'sharedWith' => 'array',
        ];
    }
}
