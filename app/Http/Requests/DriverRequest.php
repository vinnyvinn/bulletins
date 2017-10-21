<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'identification_type' => 'required',
            'identification_number' => 'required|unique:drivers',
        ];

        if ($this->method() == 'PUT') {
            $rules['identification_number'] = 'required|unique:drivers,identification_number,' . $this->id;
        }

        return $rules;
    }
}
