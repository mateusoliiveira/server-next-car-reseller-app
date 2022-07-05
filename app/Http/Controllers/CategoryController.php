<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;

class CategoryController extends Controller
{
    protected $model;
    public function __construct(
        CategoryRepositoryInterface $model,
        )
    {
        $this->model = $model;
    }

    public function index()
    {
       return $this->model->get();
    }

    public function indexWith()
    {
       return $this->model->with('vehicles.categories')->get();
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
       return $this->model->show($id);
    }

    public function with($id)
    {
       return $this->model->with('vehicles')->find($id);
    }

    public function destroy($id)
    {
       return $this->model->delete($id);
    }
}
