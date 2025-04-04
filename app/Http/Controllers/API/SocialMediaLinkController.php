<?php

namespace App\Http\Controllers\API;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SocialMediaLinkRepository;
use Illuminate\Support\Facades\Validator;

class SocialMediaLinkController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private  SocialMediaLinkRepository $SocialMediaLinkRepository,)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $links = $this->SocialMediaLinkRepository->index();
            return ApiResponseClass::sendResponse($links, 'All links retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving links: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية لاضافة منصة جديده", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'platform' => ['required','string','max:255'],
            'url' =>['required','url','max:255'],
            'icon' =>['required','string','max:255'],
            'is_active' =>['boolean'],
        ], [
            'platform.required' => 'حقل اسم المنصة مطلوب',
            'platform.string' => 'يجب أن يكون اسم المنصة نصاً',
            'platform.max' => 'يجب ألا يتجاوز اسم المنصة 255 حرفاً',
            'url.required' => 'حقل الرابط مطلوب',
            'url.url' => 'يجب إدخال رابط صحيح (يبدأ بـ http:// أو https://)',
            'url.max' => 'يجب ألا يتجاوز طول الرابط 255 حرفاً',
            'icon.required' => 'حقل الأيقونة مطلوب',
            'icon.string' => 'يجب أن تكون الأيقونة نصاً',
            'icon.max' => 'يجب ألا يتجاوز اسم الأيقونة 255 حرفاً',
            'is_active.boolean' => 'يجب أن تكون حالة التفعيل إما صحيحة أو خاطئة',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['platform','url','icon','is_active']);
            $Link=$this->SocialMediaLinkRepository->store($fields);
            return ApiResponseClass::sendResponse($Link,'تم حفظ المنصة بنجاح');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Link: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية لعرض المنصة ", [], 403);
        }
        try {
            $Link = $this->SocialMediaLinkRepository->getById($id);
            return ApiResponseClass::sendResponse($Link, " data getted  successfully");
        } catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Link: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if(!Auth::user()->has_role('admin')){
            return ApiResponseClass::sendError("ليس لديك صلاحية لتعديل المنصة ", [], 403);
        }
        $validator = Validator::make($request->all(), [
            'platform' => ['sometimes','string','max:255'],
            'url' =>['sometimes','url','max:255'],
            'icon' =>['sometimes','string','max:255'],
            'is_active' =>['boolean'],
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['platform','url','icon','is_active']);
            $Link=$this->SocialMediaLinkRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($Link,'Link is updated successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated Link: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            if(!Auth::user()->has_role('admin')){
                return ApiResponseClass::sendError("ليس لديك صلاحية لحدف المنصة", [], 403);
            }
            $Link=$this->SocialMediaLinkRepository->getById($id);
            if($this->SocialMediaLinkRepository->delete($Link->id)){
                return ApiResponseClass::sendResponse($Link, "{$Link->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Link with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Link: ' . $e->getMessage());
        }
    }
}
