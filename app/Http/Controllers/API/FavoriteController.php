<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\FavoriteRepository;

class FavoriteController extends Controller
{
    
    /**
     * Create a new class instance.
     */
    public function __construct(private FavoriteRepository $FavoriteRepository,)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Favorites=$this->FavoriteRepository->index();
            return ApiResponseClass::sendResponse($Favorites, 'All Favorites retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Favorites: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'ad_id' => ['required',Rule::exists('ads','id')],
        ]);
        try {
            $fields['user_id']= Auth::id() ?? 1; //just in test
            $Favorite=$this->FavoriteRepository->store($fields);
            return ApiResponseClass::sendResponse($Favorite,'Favorite saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Favorite: ' . $e->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        try {
            $Favorite=$this->FavoriteRepository->getById($id);
            if($this->FavoriteRepository->delete($Favorite->id)){
                return ApiResponseClass::sendResponse($Favorite, "{$Favorite->id} unsaved successfully.");
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Favorite: ' . $e->getMessage());
        }
    }
}
