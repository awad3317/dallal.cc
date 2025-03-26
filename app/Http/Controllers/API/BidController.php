<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use App\Repositories\BidRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'ad_id' => ['required',Rule::exists('ads','id')],
            'amount' => ['required','numeric','min:0',
                function ($attribute, $value, $fail) use ($request) {
                $highestBid = DB::table('bids')
                    ->where('ad_id', $request->ad_id)
                    ->max('amount');
                if ($value <= ($highestBid ?? 0)) {
                    $fail('يجب أن يكون السعر أكبر من أعلى سعر حالى ('.($highestBid ?? 0).')');
                }
            },],
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['ad_id','amount']);
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
                return ApiResponseClass::sendError("Bid with ID {$id} may not be found or not deleted. Try again.");
            }
            else{
                return ApiResponseClass::sendError('You do not have permission to delete this bid.');
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Bid: ' . $e->getMessage());
        }
    }
}
