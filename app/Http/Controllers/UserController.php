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

    public function update()
    {
        $authed = $this->request->authedUser();
        $newData = $this->request->validated();
        $find = $this->model->show($authed->id);

        isset($newData["name"]) && $find->name != $newData["name"] && $find->name = $newData["name"];
        isset($newData["password"]) && $this->request->checkHashed($newData["old_password"], $find->password) &&
        $find->password = $this->request->changeHashedPass($newData["password"]);

        $toArrayUser = [
          "id" => $find->id,
          "name" => $find->name,
          "password" => $find->password,
        ];
        return $this->model->where('id', '=', $find->id)->update($toArrayUser);
    }
}
