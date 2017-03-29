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
            'name' => 'required',
            'national_id' => 'required|unique:drivers',
            'dl_number' => 'required|unique:drivers',
            'mobile' => 'required',
        ];

        if ($this->method() == 'PUT') {
            $rules['national_id'] = 'required|unique:drivers,national_id,' . $this->id;
            $rules['dl_number'] = 'required|unique:drivers,dl_number,' . $this->id;
        }

        return $rules;
    }
}
