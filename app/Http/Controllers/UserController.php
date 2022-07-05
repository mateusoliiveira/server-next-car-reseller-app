<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\Contracts\OfferRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $model;
    protected $modelOffer;
    protected $request;
    public function __construct(
        UserRepositoryInterface $model,
        OfferRepositoryInterface $modelOffer,
        UserRequest $request
        )
    {
        $this->model = $model;
        $this->modelOffer = $modelOffer;
        $this->request = $request;
    }


    public function store()
    {
        $user = $this->model->create($this->request->userHashed());
        return response()->json([
            'message' => 'Conta criada com sucesso, seja bem vindo!',
            $user,
        ], 201);
    }

    public function showOffers()
    {
       $offers = $this->modelOffer->with('vehicles.categories')->where('user_id', '=', $this->session->id)->get();
       $parsedOffers = json_encode((str_replace("'", '"', $offers)), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
       return utf8_encode($parsedOffers);
    }
}
