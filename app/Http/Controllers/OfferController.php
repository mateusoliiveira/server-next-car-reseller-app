<?php

namespace App\Http\Controllers;
use App\Http\Requests\OfferRequest;
use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Repositories\Contracts\OfferRepositoryInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;

class OfferController extends Controller
{
    protected $model;
    protected $modelBrand;
    protected $request;
    public function __construct(
        OfferRepositoryInterface $model,
        BrandRepositoryInterface $modelBrand,
        OfferRequest $request
        )
    {
        $this->model = $model;
        $this->modelBrand = $modelBrand;
        $this->request = $request;
    }


    public function index()
    {
       return $this->model->get();
    }

    public function show($id)
    {
       return $this->model->with('vehicles.categories')->find($id);
    }

    public function showByOwn()
    {
      $user = $this->request->authedUser();
      return $this->model->with('vehicles.brands')->where('user_id', '=', $user->id)->get();
    }

    public function showByVehicleName($vehicle)
    {
       return $this->model->where('title', 'LIKE', "%$vehicle%")->with('vehicles.categories')->get();
    }

    public function showByBrandId($id)
    {
      return $this->modelBrand->with('offers.vehicles.categories')->find($id);
    }

    public function store()
    {
      $user = $this->request->authedUser();
      $data = $this->request->validated();
      $data['user_id'] = $user->id;
      return $this->model->create($data);
    }

    public function insert()
    {
       return $this->model->insert((array_map(fn($request): array => [
         "id" => UuidV4::uuid4(),
         "created_at" => now(),
         "updated_at" => now(),
         ...$request
       ], $this->request->all())));
    }

    public function update($id)
    {
        $authed = $this->request->authedUser();
        $newData = $this->request->validated();
        $find = $this->model->show($id);

        if ($find->user_id !== $authed->id) return response()->json([
         'errors' => ['user_id' => 'não permitido']
        ], 401);

        $this->model->where('id', '=', $find->id)->update(['id' => $id, ...$newData]);

        return response()->json([
          'message' => 'anúncio alterado com sucesso'
        ], 200);
    }

    public function destroy($id)
    {
      $authed = $this->request->authedUser();
      $find = $this->model->show($id);

      if ($find->user_id !== $authed->id) return response()->json([
       'errors' => ['user_id' => 'não permitido']
      ], 401);

      return $this->model->delete($id);
    }
}
