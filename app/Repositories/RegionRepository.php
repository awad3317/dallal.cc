<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\region;
use Illuminate\Support\Facades\DB;

class RegionRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return region::paginate(10);
    }

    public function getById($id): region
    {
        return region::findOrFail($id);
    }

    public function store(array $data): region
    {
        return region::create($data);
    }

    public function update(array $data, $id): region
    {
        $region = region::findOrFail($id);
        $region->update($data);
        return $region;
    }

    public function delete($id): bool
    {
        return Region::where('id', $id)->delete() > 0;
    }
    
    public function getParents(){
        return Region::whereNull('parent_id')->get();
    }

    public function getChildren($id)
    {
        return Region::where('parent_id', $id)->get();
    }
}
