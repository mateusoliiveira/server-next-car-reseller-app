<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AuthRequest extends FormRequest
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
            'email' => 'string|email',
            'password' => 'string'
        ];
    }

    public function authedToken()
    {
        $token = Auth::attempt($this->validated());
        if (!$token)
        { return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }
        return $token;
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

    public function authedLogout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Logged Out',
        ], 200);
    }

    public function authedRefreshToken()
    {
        $refreshed = Auth::refresh();
        return $refreshed;
    }
}
