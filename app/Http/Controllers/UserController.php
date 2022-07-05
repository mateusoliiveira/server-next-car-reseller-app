<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\OfferRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $model;
    protected $modelOffer;
    protected $session;
    public function __construct(
        UserRepositoryInterface $model,
        OfferRepositoryInterface $modelOffer
        )
    {
        $this->model = $model;
        $this->modelOffer = $modelOffer;
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
