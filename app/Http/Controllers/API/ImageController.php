<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private ImageRepository $ImageRepository,private ImageService $ImageService,private AdRepository $AdRepository)
    {
        //
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ad_id' => ['required',Rule::exists('ads','id')],
            'image'=>['required','image','max:2048']
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['ad_id','image']);
            $ad = $this->AdRepository->getById($fields['ad_id']);
            if ($ad->images()->count() >= 7) {
                return ApiResponseClass::sendError("تم الوصول إلى الحد الأقصى لعدد الصور المسموح به (7 صور) ولا يمكن إضافة المزيد.");
            }
            $fields['image_url']=$this->ImageService->saveImage($fields['image'],'additional_image');
            $Image=$this->ImageRepository->store($fields);
            return ApiResponseClass::sendResponse($Image,'Image saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Image: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'image'=>['required','image','max:2048']
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $fields=$request->only(['image']);
            $oldImage = $this->ImageRepository->getById($id);
            $this->ImageService->deleteImage($oldImage->image_url);
            $imagePath = $this->ImageService->saveImage($fields['image'], 'additional_image');
            unset($fields);
            $fields['image_url']=$imagePath;
            $Image=$this->ImageRepository->update($fields, $id);
            return ApiResponseClass::sendResponse($Image,'Image is updated successfully.');
        }catch (Exception $e) {
            return ApiResponseClass::sendError('Error Update Image: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Image=$this->ImageRepository->getById($id);
            $this->ImageService->deleteImage($Image->image_url);
            if($this->ImageRepository->delete($Image->id)){
                return ApiResponseClass::sendResponse($Image, "{$Image->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Image with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Image: ' . $e->getMessage());
        }
    }
}
