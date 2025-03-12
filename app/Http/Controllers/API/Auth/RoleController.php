<?php

namespace App\Http\Controllers\api\auth;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private RoleRepository $RoleRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $roles = $this->RoleRepository->index();
            return ApiResponseClass::sendResponse($roles, 'All roles retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving roles: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'name' => ['required','string',Rule::unique('roles','name')],
            'display_name' => ['required','string'],
            'permissions' => ['required','array'],
            'permissions.*' => [Rule::exists('permissions','id')],
        ]);
        try {
            if (isset($request->permissions) && !is_array($request->permissions)) {
                $fields['permissions']= [$request->permissions];
            }
            $role = $this->RoleRepository->store($fields);
            $role->permissions()->sync($fields['permissions']);
            return ApiResponseClass::sendResponse($role, "{$role['name']} created successfully.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error creating role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $role = $this->RoleRepository->getById($id);
            return ApiResponseClass::sendResponse($role, 'Role retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving role: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $role = $this->RoleRepository->getById($id);
            if ($this->RoleRepository->delete($id)) {
                return ApiResponseClass::sendResponse($role, "{$role->role_name} deleted successfully.");
            }
            return ApiResponseClass::sendError("role with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting role: ' . $e->getMessage());
        }
    }
}
