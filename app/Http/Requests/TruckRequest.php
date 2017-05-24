<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TruckRequest extends FormRequest
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
            'plate_number' => 'required|unique:trucks',
            'max_load' => 'required',
        ];

        if ($this->method() == 'PUT') {
            $rules['plate_number'] = 'required|unique:trucks,plate_number,' . $this->id;
        }

        return $rules;
    }
}
