<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Repository;
use App\Models\Vehicle;
use Ramsey\Uuid\Rfc4122\UuidV4;

class VehicleController extends Controller
{
    protected $vehicle;
    public function __construct(Vehicle $vehicle)
    {
       $this->model = new Repository($vehicle);
    }

    public function index()
    {
       return $this->model->with('categories')->with('brands')->get();
    }

    public function store(Request $request)
    {
       return $this->model->create($request->all());
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

    public function show($id)
    {
       return $this->model->with('categories')->with('brands')->find($id);
    }

    public function destroy($id)
    {
       return $this->model->delete($id);
    }
}