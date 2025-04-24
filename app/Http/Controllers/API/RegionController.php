<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\RegionRepository;
use Illuminate\Support\Facades\Validator;


class RegionController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private RegionRepository $RegionRepository,)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $Regions=$this->RegionRepository->index($request->name);
            return ApiResponseClass::sendResponse($Regions, 'All Regions retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Regions: ' . $e->getMessage());
        }
    }

    public function getParents()
    {
        try {
            // Retrieve all parent regions from the repository
            $Parents=$this->RegionRepository->getParents();
            return ApiResponseClass::sendResponse($Parents,'All Parents retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Parents: ' . $e->getMessage());
        }

    }

    public function getChildren($slug)
    {
        try {
             // Retrieve all Children regions from the repository
            $Children=$this->RegionRepository->getChildren($slug);
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
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية الإضافة منطقة", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('regions','name')],
            'parent_id' => ['nullable',Rule::exists('regions','id')],
            'latitude' => ['required','numeric','between:-90,90'],
            'longitude' =>['required','numeric','between:-180,180']
        ], [
           'name.required'=>'يجب إدخال أسم المنطقة',
           'name.string'=>'يجب أن يكون الاسم نصاً',
           'name.unique'=>'ألاسم موجود من قبل في النظام',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['name','parent_id','latitude','longitude']);
            $Region=$this->RegionRepository->store($fields);
            return ApiResponseClass::sendResponse($Region,'Region saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Region: ' . $e->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $Region = $this->RegionRepository->getById($id);
            return ApiResponseClass::sendResponse($Region, " data getted successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Region: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية لتعديل على منطقة", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required','string',Rule::unique('regions','name')->ignore($id)],
            'parent_id' => ['nullable',Rule::exists('regions','id')],
            'latitude' => ['required','numeric','between:-90,90'],
            'longitude' =>['required','numeric','between:-180,180']
        ], [
           'name.required'=>'يجب إدخال أسم المنطقة',
           'name.string'=>'يجب أن يكون الاسم نصاً',
           'name.unique'=>'ألاسم موجود من قبل في النظام',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['name','parent_id','latitude','longitude']);
            $Region=$this->RegionRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($Region,'Region is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated Region: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if(!Auth::user()->has_role('admin')){
                return ApiResponseClass::sendError("ليس لديك صلاحية لحدف منطقة", [], 403);
            }
            $Region=$this->RegionRepository->getById($id);
            if($this->RegionRepository->delete($Region->id)){
                return ApiResponseClass::sendResponse($Region, "{$Region->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Region with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Region: ' . $e->getMessage());
        }
    }
}
