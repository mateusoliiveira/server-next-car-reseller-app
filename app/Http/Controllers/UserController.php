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

    public function index()
    {
        $authed = $this->request->authedUser();
        return $this->model->with('offers.vehicles.brands')->find($authed->id);
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

        $infoToUpdate = [
          "id" => $find->id,
        ];

        if(isset($newData["name"])) {
          $infoToUpdate["name"] = $newData["name"];
          $this->model->where('id', '=', $find->id)->update($infoToUpdate);
          return response()->json([
            'message' => 'nome alterado com sucesso para '.$newData["name"]
          ], 200);
        }

        $checkIfActualPassIsRight = $this->request->checkHashed($newData["old_password"], $find->password);
          if(!$checkIfActualPassIsRight) {
            return response()->json([
              'errors' => ['old_password' => 'senha atual incorreta']
            ], 401);
          }

        $infoToUpdate["password"] = $this->request->changeHashedPass($newData["password"]);
        $this->model->where('id', '=', $find->id)->update($infoToUpdate);

        return response()->json([
          'message' => 'senha alterada com sucesso'
        ], 200);
    }
}
