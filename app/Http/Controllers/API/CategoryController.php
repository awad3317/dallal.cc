<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Validator;

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
        if(!Auth::user()->has_permission('create-categorie')){
            return ApiResponseClass::sendError("ليس لديك صلاحية الإضافة فئة جديده", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('categories','name')],
            'parent_id' => ['nullable',Rule::exists('categories','id')]
        ], [
           'name.required'=>'يجب إدخال أسم الصنف',
           'name.string'=>'يجب أن يكون الاسم نصاً',
           'name.unique'=>'ألاسم موجود من قبل في النظام',
        ]);
    
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['name','parent_id']);
            $Categorie=$this->CategoryRepository->store($fields);
            return ApiResponseClass::sendResponse($Categorie,'تم حفظ الفئة بنجاح');
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
        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('categories','name')->ignore($id)],
            'parent_id' => ['nullable',Rule::exists('categories','id')]
        ], [
           'name.required'=>'يجب إدخال أسم الصنف',
           'name.string'=>'يجب أن يكون الاسم نصاً',
           'name.unique'=>'ألاسم موجود من قبل في النظام',
        ]);
    
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['name','parent_id']);
            $category=$this->CategoryRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($category,'category is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated category: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            if(!Auth::user()->has_permission('destroy-categorie')){
                return ApiResponseClass::sendError("ليس لديك صلاحية لحدف فئة", [], 403);
            }
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
