<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Repositories\Repository;
use App\Models\User;

class UserController extends Controller
{
    protected $model;
    protected $session;
    public function __construct(User $user)
    {
       $this->model = new Repository($user);
       try {
         $this->session = Auth::user();
       } catch (\Throwable $th) {
         return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
            'data' => $th
        ], 401); 
       }
       
    }
    
    public function show()
    {
       return $this->model->show($this->session->id);
    }

    public function with()
    {
       return $this->model->with('offers.vehicles')->find($this->session->id);
    }
}