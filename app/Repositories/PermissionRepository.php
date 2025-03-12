<?php 

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Permission;

class PermissionRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Permissions with pagination.
     *
     */
    public function index()
    {
        return Permission::all();
    }

    /**
     * Retrieve a Permission by ID.
     */
    public function getById($id): Permission
    {
        return Permission::findOrFail($id);
    }

    /**
     * Store a new Permission.
     */
    public function store(array $data): Permission
    {
        return Permission::create($data);
    }

    /**
     * Update an existing Permission.
     */
    public function update(array $data, $id): Permission
    {
        $Permission = Permission::findOrFail($id);
        $Permission->update($data);
        return $Permission;
    }

    /**
     * Delete a Permission by ID.
     */
    public function delete($id): bool
    {
        return Permission::where('id', $id)->delete() > 0;
    }
    
}


