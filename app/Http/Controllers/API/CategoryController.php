<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private CategoryRepository $CategoryRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Categories=$this->CategoryRepository->index();
            return ApiResponseClass::sendResponse($Categories, 'All Categories retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Categories: ' . $e->getMessage());
        }
    }

    public function getParents()
    {
        try {
            $Parents=$this->CategoryRepository->getParents();
            return ApiResponseClass::sendResponse($Parents,'All Parents retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Parents: ' . $e->getMessage());
        }
    }

    public function getChildren($id)
    {
        try {
            $Children=$this->CategoryRepository->getChildren($id);
            return ApiResponseClass::sendResponse($Children,'All Children retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Children: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'name' => ['required','string'],
            'parent_id' => ['nullable',Rule::exists('categories','id')],
            'image' => ['image',Rule::requiredIf(function () use ($request) {return is_null($request->parent_id);}),],
        ]);
        try {
            $Categorie=$this->CategoryRepository->store($fields);
            return ApiResponseClass::sendResponse($Categorie,'category saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save category: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $Category = $this->CategoryRepository->getById($id);
            return ApiResponseClass::sendResponse($Category, " data getted  successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Category: ' . $e->getMessage());
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
            $category=$this->CategoryRepository->getById($id);
            if($this->CategoryRepository->delete($category->id)){
                return ApiResponseClass::sendResponse($category, "{$category->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Category with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Category: ' . $e->getMessage());
        }
    }
}
