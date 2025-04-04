<?php

namespace App\Http\Controllers\api\auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\RoleRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية الإضافة دور جديد", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('roles','name')],
            'display_name' => ['required','string'],
            'permissions' => ['required','array'],
            'permissions.*' => [Rule::exists('permissions','id')],
        ],[
            'name.required' => 'يجب إدخال إدخال اسم الدور',
            'name.string' => 'يجب أن يكون اأسم الدور نصاً',
            'name.unique' => 'ألاسم موجد في النظام من قبل',
            'display_name.required'=>'يجب كتابة اسم عرض الدور',
            'display_name.string'=>'يجب أن يكون اسم عرض الدور نصاً'
        ]);
        if ($validator->fails()) {
           return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        $fields=$request->only(['name','display_name','permissions']);
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
    public function update(Request $request, $id)
    {
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية لتعديل دور", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'string', Rule::unique('roles', 'name')->ignore($id)],
            'display_name' => ['sometimes', 'string'],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => [Rule::exists('permissions', 'id')],
        ], [
            'name.string' => 'يجب أن يكون اسم الدور نصاً',
            'name.unique' => 'الاسم موجود في النظام من قبل',
            'display_name.string' => 'يجب أن يكون اسم عرض الدور نصاً',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields = $request->only(['name', 'display_name', 'permissions']);
            $role = $this->RoleRepository->getById($id);
            if (isset($fields['permissions'])) {
                if (!is_array($fields['permissions'])) {
                    $fields['permissions'] = [$fields['permissions']];
                }
                $role->permissions()->sync($fields['permissions']);
            }
            $updatedRole = $this->RoleRepository->update($fields, $id);
            return ApiResponseClass::sendResponse($updatedRole, "{$updatedRole['name']} updated successfully.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updating role: ' . $e->getMessage());
        }
    }   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            if(!Auth::user()->has_role('admin')){
                return ApiResponseClass::sendError("ليس لديك صلاحية لحدف دور", [], 403);
            }
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
