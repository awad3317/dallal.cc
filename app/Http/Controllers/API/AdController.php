<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private AdRepository $AdRepository,private ImageService $ImageService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ads = $this->AdRepository->index();
            return ApiResponseClass::sendResponse($ads, 'All ads retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving ads: ' . $e->getMessage());
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'category_id' =>['required',Rule::exists('categories','id')],
            'region_id' => ['required',Rule::exists('regions','id')],
            'title' => ['required','string','max:255'],
            'description' =>['required','string'],
            'price' => ['required','numeric','min:0'],
            'primary_image' =>['required','image','max:2048'],
            'status'=>['required','in:جديد,مستعمل'],
            'sale_option_id' =>['required',Rule::exists('sale_options','id')],
            'images'=>['nullable', 'array', 'max:7'],
            'images.*'=>['image', 'max:2048'],
        ]);
        try {
            $fields['user_id']=Auth::id();
            $fields['primary_image']=$this->ImageService->saveImage($fields['primary_image']);
            $ad=$this->AdRepository->store($fields);
            if ($request->hasFile('images')) {
                $Images = $request->file('images');
                foreach ($Images as $image) {
                    $imagePath = $this->ImageService->saveImage($image,'additional_image');
                    $ad->images()->create(['image_url' => $imagePath]);
                }
            }
            return ApiResponseClass::sendResponse($ad,'Ad saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save Ad: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $ad = $this->AdRepository->getById($id);
            return ApiResponseClass::sendResponse($ad, " data getted  successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Ad: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ad=$this->AdRepository->getById($id);
            if($this->AdRepository->delete($ad->id)){
                return ApiResponseClass::sendResponse($ad, "{$ad->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Ad with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Ad: ' . $e->getMessage());
        }
    }
}
