<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;

class ImageController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private ImageRepository $ImageRepository,private ImageService $ImageService)
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
        $fields=$request->validate([
            'ad_id' => ['required',Rule::exists('ads','id')],
            'image'=>['required','image','max:2048']
        ]);
        try {
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
        $fields=$request->validate([
            'image'=>['required','image','max:2048']
        ]);
        try {
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
            if($this->ImageRepository->delete($Image->id)){
                return ApiResponseClass::sendResponse($Image, "{$Image->id} unsaved successfully.");
            }
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Image: ' . $e->getMessage());
        }
    }
}
