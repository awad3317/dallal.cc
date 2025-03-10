<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\LikeRepository;

class LikeController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private LikeRepository $LikeRepository)
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Like=$this->LikeRepository->getById($id);
            if($this->LikeRepository->delete($Like->id)){
                return ApiResponseClass::sendResponse($Like, "{$Like->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Like with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Like: ' . $e->getMessage());
        }

    }
}
