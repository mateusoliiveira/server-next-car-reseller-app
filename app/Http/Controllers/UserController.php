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

    public function userOffers()
    {
       $offers = $this->modelOffer->with('vehicles.categories')->where('user_id', '=', $this->session->id)->get();
       return json_encode($offers);
    }
    
    public function show()
    {
      $mainInfo = $this->model->show($this->session->id);
      $offersInfo = $this->userOffers();
      $userData = (object)array_merge([$mainInfo], ["offers" => $offersInfo]);
    
     return $userData;
    }

    
}