<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
            return [
                'username' => 'required|unique:users,username,' . $this->user,
                'password' => 'nullable|confirmed'
            ];
        }

        return [
            'username' => 'required|unique:users',
            'password' => 'required|confirmed'
        ];
    }
}
