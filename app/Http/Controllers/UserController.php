<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Repository;
use App\Models\User;

class UserController extends Controller
{
   protected $model;
   protected $modelOffer;
   protected $session;
    public function __construct(User $user, Offer $offer)
    {
       $this->model = new Repository($user);
       $this->modelOffer = new Repository($offer);
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
    
    public function showOffers()
    {
       $offers = $this->modelOffer->with('vehicles.categories')->where('user_id', '=', $this->session->id)->get();
       $parsedOffers = json_encode((str_replace("'", '"', $offers)), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);   
       return utf8_encode($parsedOffers);
    }
}