<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use Illuminate\Http\Request;
use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Repositories\Contracts\OfferRepositoryInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;

class OfferController extends Controller
{
    protected $model;
    protected $modelBrand;
    public function __construct(
        OfferRepositoryInterface $model,
        BrandRepositoryInterface $modelBrand
        )
    {
        $this->model = $model;
        $this->modelBrand = $modelBrand;
    }


    public function index()
    {
       return $this->model->get();
    }

    public function show($id)
    {
       return $this->model->with('vehicles.categories')->find($id);
    }

    public function showByVehicleName($vehicle)
    {
       return $this->model->where('title', 'LIKE', "%$vehicle%")->with('vehicles.categories')->get();
    }

    public function showByBrandId($id)
    {
      return $this->modelBrand->with('offers.vehicles.categories')->find($id);
    }

    public function store(OfferRequest $request)
    {
      $user = $request->authedUser();
      //  if($request->file('file')){
      //     $file = $request->file('file');
      //     $filename = date('YmdHi').$file->getClientOriginalName();
      //     $file->move(public_path('public/image/offers'),$filename);
      //     $data['picture'] = $filename;
      // }
      $data = $request->all();
      $data['user_id'] = $user->id;
      return $this->model->create($data);
    }

    public function insert(Request $request)
    {
       return $this->model->insert((array_map(fn($request): array => [
         "id" => UuidV4::uuid4(),
         "created_at" => now(),
         "updated_at" => now(),
         ...$request
       ], $request->all())));
    }

    public function destroy($id)
    {
       return $this->model->delete($id);
    }
}
