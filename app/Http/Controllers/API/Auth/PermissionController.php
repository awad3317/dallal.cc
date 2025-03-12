<?php

namespace App\Http\Controllers\api\auth;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\PermissionRepository;

class PermissionController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private PermissionRepository $PermissionRepository)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $permissions = $this->PermissionRepository->index();
            return ApiResponseClass::sendResponse($permissions, 'All permissions retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving permissions: ' . $e->getMessage());
        }
    }
}
