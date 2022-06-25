<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\Repository;

class AuthController extends Controller
{
    protected $model;
    public function __construct(User $user)
    {
       $this->model = new Repository($user);
    }

    public function login(UserRequest $request)
    {   
        $validated = $request->validated();
        $data = $validated;
        $token = Auth::attempt($data);
        
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = Auth::user();
        return response()->json([
                'message' => 'Usuário logado',
                'user' => $user,
                'token' => $token,
            ]);
    }

    public function register(UserRequest $request){
        $validated = $request->validated();
        $data = $validated;
        $data['password'] = Hash::make($data['password']);
        $user = $this->model->create($data);
        $token = Auth::login($user);
        return response()->json([
            'message' => 'Sua conta foi criada :)',
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {
        return response()->json([
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

}