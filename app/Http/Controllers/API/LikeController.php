<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\LikeService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\LikeRepository;

class LikeController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private LikeRepository $LikeRepository,private LikeService $LikeService)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $fields=$request->validate([
            'ad_id' => ['required',Rule::exists('ads','id')],
        ]);
        try {
            $like = $this->LikeService->likeAd($fields['ad_id']);
            return ApiResponseClass::sendResponse($like, 'Ad liked successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error liking ad: ' . $e->getMessage());
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ad_id)
    {
        try {
            if($this->LikeService->unlikeAd($ad_id)){
                return ApiResponseClass::sendResponse(null, 'Ad unliked successfully.');
            }
            return ApiResponseClass::sendError('Ad not liked or already unliked.');
            return ApiResponseClass::sendError("Like with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error unliking ad: ' . $e->getMessage());
        }

    }
}
