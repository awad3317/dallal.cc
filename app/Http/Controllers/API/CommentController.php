<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\CommentRepository;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private CommentRepository $CommentRepository,)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $Comments=$this->CommentRepository->index();
            return ApiResponseClass::sendResponse($Comments, 'All Comments retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving Comments: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_id' => ['required',Rule::exists('ads','id')],
            'comment_text' => ['required','string'],
        ],[
            'ad_id.required' => 'حقل معرف الإعلان مطلوب.',
            'ad_id.exists' => 'معرف الإعلان المحدد غير موجود.',
            'comment_text.required' => 'حقل التعليق مطلوب.',
            'comment_text.string' => 'يجب أن يكون التعليق نصياً.',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['ad_id','comment_text']);
            $fields['user_id']= Auth::id();
            $Comment=$this->CommentRepository->store($fields);
            return ApiResponseClass::sendResponse($Comment,'Comment saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Comment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Comment=$this->CommentRepository->getById($id);
            if ($Comment->user_id !== Auth::id() && !Auth::user()->has_role('admin')) {
                return ApiResponseClass::sendError("ليس لديك صلاحية لحدف هدا التعليق", [], 403);
            }
            if($this->CommentRepository->delete($Comment->id)){
                return ApiResponseClass::sendResponse($Comment, "{$Comment->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Comment with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Comment: ' . $e->getMessage());
        }
    }
}
