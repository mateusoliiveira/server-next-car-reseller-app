<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

    public function userHashed() {
        $user = $this->validated();
        $user['password'] = Hash::make($user['password']);
        return $user;
    }

    public function checkHashed($newPass, $hashedToVerify) {
        return Hash::check($newPass, $hashedToVerify);
    }

    public function changeHashedPass($passToReHash) {
        return Hash::make($passToReHash);
    }

    public function rules()
    {
        if($this->method() != 'POST' && $this->method() != 'PATCH') return [];

        if($this->method() == 'PATCH') return [
            'name' => 'sometimes|string|max:50',
            'password' => 'sometimes|string|min:6|max:20',
            'old_password' => 'sometimes|string|min:6|max:20',
        ];

        return [
            'email' => 'required|string|email|min:10|max:50|unique:users,email',
            'name' => 'required|string|max:50',
            'password' => 'required|string|min:6|max:20',
        ];
    }

    public function authedUser()
    {
        $user = Auth::user();
        if(!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        return $user;
    }
}
