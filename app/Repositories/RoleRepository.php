<?php 

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Role;

class RoleRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Roles with pagination.
     *
     *  @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Role::paginate(10);
    }

    /**
     * Retrieve a Role by ID.
     */
    public function getById($id): Role
    {
        return Role::with(['permissions'])->findOrFail($id);
    }

    /**
     * Store a new Role.
     */
    public function store(array $data): Role
    {
        return Role::create($data);
    }

    /**
     * Update an existing Role.
     */
    public function update(array $data, $id): Role
    {
        $Role = Role::findOrFail($id);
        $Role->update($data);
        return $Role;
    }

    /**
     * Delete a Role by ID.
     */
    public function delete($id): bool
    {
        return Role::where('id', $id)->delete() > 0;
    }
    
}


