<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Repositories\RegionRepository;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

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
    public function index()
    {
        try {
            $Regions=$this->RegionRepository->index();
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

    public function getChildren($id)
    {
        try {
             // Retrieve all Children regions from the repository
            $Children=$this->RegionRepository->getChildren($id);
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
            'parent_id' => ['nullable',Rule::exists('Regions','id')]
        ]);
        try {
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
        $fields=$request->validate([
            'name' => ['required','string'],
            'parent_id' => ['nullable',Rule::exists('Regions','id')]
        ]);
        try {
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
            $Region=$this->RegionRepository->getById($id);
            if($this->RegionRepository->delete($Region->id)){
                return ApiResponseClass::sendResponse($Region, "{$Region->id} unsaved successfully.");
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Region: ' . $e->getMessage());
        }
    }
}
