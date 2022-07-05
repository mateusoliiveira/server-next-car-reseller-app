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

    public function rules()
    {
        return [
            'email' => 'required|string|email|min:10|max:50|unique:users,email',
            'name' => 'required|string|max:50',
            'password' => 'required|string|min:6|max:20',
        ];
    }
}
