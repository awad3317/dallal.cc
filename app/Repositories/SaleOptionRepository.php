<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\SaleOption;

class SaleOptionRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function index()
    {
        return SaleOption::all();
    }

    public function getById($id): SaleOption
    {
        return SaleOption::findOrFail($id);
    }

    public function store(array $data): SaleOption
    {
        return SaleOption::create($data);
    }

    public function update(array $data, $id): SaleOption
    {
        $SaleOption = SaleOption::findOrFail($id);
        $SaleOption->update($data);
        return $SaleOption;
    }

    public function delete($id): bool
    {
        return SaleOption::where('id', $id)->delete() > 0;
    }
    
}
