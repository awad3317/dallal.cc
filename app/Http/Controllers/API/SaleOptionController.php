<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\SaleOptionRepository;

class SaleOptionController extends Controller
{
    
    /**
     * Create a new class instance.
     */
    public function __construct(private SaleOptionRepository $SaleOptionRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $SaleOptions=$this->SaleOptionRepository->index();
            return ApiResponseClass::sendResponse($SaleOptions, 'All SaleOptions retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving SaleOptions: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'name' => ['required','string','max:255',Rule::unique('sale_options','name')],
        ]);
        try {
            $SaleOption=$this->SaleOptionRepository->store($fields);
            return ApiResponseClass::sendResponse($SaleOption,'SaleOption saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save SaleOption: ' . $e->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $SaleOption = $this->SaleOptionRepository->getById($id);
            return ApiResponseClass::sendResponse($SaleOption, " data getted successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned SaleOption: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $fields=$request->validate([
            'name' => ['sometimes','string','max:255',Rule::unique('sale_options','name')->ignore($id)],
        ]);
        try {
            $SaleOption=$this->SaleOptionRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($SaleOption,'SaleOption is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated SaleOption: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $SaleOption=$this->SaleOptionRepository->getById($id);
            if($this->SaleOptionRepository->delete($SaleOption->id)){
                return ApiResponseClass::sendResponse($SaleOption, "{$SaleOption->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("SaleOption with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting SaleOption: ' . $e->getMessage());
        }
    }
}
