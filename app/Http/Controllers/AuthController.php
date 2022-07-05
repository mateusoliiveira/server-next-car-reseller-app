<?php

namespace App\Http\Controllers;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Contracts\UserRepositoryInterface;

class AuthController extends Controller
{
    protected $model;
    protected $request;
    public function __construct(
        UserRepositoryInterface $model,
        AuthRequest $request
        )
    {
        $this->model = $model;
        $this->request = $request;
    }

    public function login()
    {
        $token = $this->request->authedToken();
        $user = $this->request->authedUser();
        return response()->json([
                'message' => 'Usuário logado',
                'user' => $user,
                'token' => $token,
            ]);
    }

    public function logout()
    {
       $this->request->authedLogout();
    }

    public function me()
    {
        return response()->json([
            'user' => $this->request->authedUser(),
        ], 200);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => $this->request->authedUser(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
