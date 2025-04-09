<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function index($name=null): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        if($name){
            return Category::where('name', 'LIKE', "%{$name}%")->paginate(10);
        }
        return Category::paginate(10);
    }

    public function getById($id): Category
    {
        return Category::findOrFail($id);
    }

    public function store(array $data): Category
    {
        return Category::create($data);
    }

    public function update(array $data, $id): Category
    {
        $Category = Category::findOrFail($id);
        $Category->update($data);
        return $Category;
    }

    public function delete($id): bool
    {
        return Category::where('id', $id)->delete() > 0;
    }
    
    public function getParents(){
        return Category::whereNull('parent_id')->get();
    }

    public function getChildren($id)
    {
        return Category::where('parent_id', $id)->get();
    }
}
