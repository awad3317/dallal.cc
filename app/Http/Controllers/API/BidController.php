<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Repositories\BidRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    
     /**
     * Create a new class instance.
     */
    public function __construct(private BidRepository $BidRepository,)
    {
        //
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Bids=$this->BidRepository->index();
            return ApiResponseClass::sendResponse($Bids, 'All Bids retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Bids: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'ad_id' => ['required',Rule::exists('ads','id')],
            'amount' => ['required','numeric','min:0'],
        ]);
        try {
            $fields['user_id']=Auth::id();
            $Bid=$this->BidRepository->store($fields);
            return ApiResponseClass::sendResponse($Bid,'Bid saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Bid: ' . $e->getMessage());
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $Bid = $this->BidRepository->getById($id);
            return ApiResponseClass::sendResponse($Bid, " data getted successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Bid: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $Bid = $this->BidRepository->getById($id);
        if(!$Bid){
            return ApiResponseClass::sendError('Bid not found');
        }
        $fields=$request->validate([
            'ad_id' =>['sometimes',Rule::exists('ads','id')],
            'amount' => ['sometimes','numeric','min:0'],
        ]);
        try {
            $Bid=$this->BidRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($Bid,'Bid is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated Bid: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Bid=$this->BidRepository->getById($id);
            if($Bid->user_id == Auth::id()){
                if($this->BidRepository->delete($Bid->id)){
                    return ApiResponseClass::sendResponse($Bid, "{$Bid->id} unsaved successfully.");
                }
            }
            else{
                return ApiResponseClass::sendError('You do not have permission to delete this bid.');
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Bid: ' . $e->getMessage());
        }
    }
}
