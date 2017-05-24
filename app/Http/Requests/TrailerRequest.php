<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrailerRequest extends FormRequest
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
        $rules = [
            'trailer_number' => 'required|unique:trailers',
        ];

        if ($this->method() == 'PUT') {
            $rules['trailer_number'] = 'required|unique:trailers,trailer_number,' . $this->trailer->id;
        }

        return $rules;
    }
}
