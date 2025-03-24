<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Jobs\recordViewJob;
use Illuminate\Http\Request;
use App\Services\ViewService;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

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
    public function index(Request $request)
    {
        try {
            $ads = $this->AdRepository->index($request->region_id,$request->category_id);
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
    $validator = Validator::make($request->all(), [
        'category_id' => ['required', Rule::exists('categories', 'id')],
        'region_id' => ['required', Rule::exists('regions', 'id')],
        'title' => ['required', 'string', 'max:255'],
        'description' => ['required', 'string'],
        'price' => ['required', 'numeric', 'min:0'],
        'primary_image' => ['required', 'image', 'max:2048'],
        'status' => ['required', 'in:جديد,مستعمل'],
        'sale_option_id' => ['required', Rule::exists('sale_options', 'id')],
        'image1' => ['nullable', 'image', 'max:2048'],
        'image2' => ['nullable', 'image', 'max:2048'],
        'image3' => ['nullable', 'image', 'max:2048'],
        'image4' => ['nullable', 'image', 'max:2048'],
        'image5' => ['nullable', 'image', 'max:2048'],
        'image6' => ['nullable', 'image', 'max:2048'],
        'image7' => ['nullable', 'image', 'max:2048'],
    ], [
        'category_id.required' => 'حقل التصنيف مطلوب.',
        'category_id.exists' => 'التصنيف المحدد غير موجود.',
        'region_id.required' => 'حقل المنطقة مطلوب.',
        'region_id.exists' => 'المنطقة المحددة غير موجودة.',
        'title.required' => 'حقل العنوان مطلوب.',
        'title.string' => 'يجب أن يكون العنوان نصًا.',
        'title.max' => 'يجب ألا يتجاوز العنوان 255 حرفًا.',
        'description.required' => 'حقل الوصف مطلوب.',
        'description.string' => 'يجب أن يكون الوصف نصًا.',
        'price.required' => 'حقل السعر مطلوب.',
        'price.numeric' => 'يجب أن يكون السعر رقمًا.',
        'price.min' => 'يجب أن يكون السعر أكبر من أو يساوي 0.',
        'primary_image.required' => 'حقل الصورة الرئيسية مطلوب.',
        'primary_image.image' => 'يجب أن تكون الصورة الرئيسية ملف صورة.',
        'primary_image.max' => 'يجب ألا تتجاوز الصورة الرئيسية 2 ميجابايت.',
        'status.required' => 'حقل الحالة مطلوب.',
        'status.in' => 'الحالة يجب أن تكون إما "جديد" أو "مستعمل".',
        'sale_option_id.required' => 'حقل خيار البيع مطلوب.',
        'sale_option_id.exists' => 'خيار البيع المحدد غير موجود.',
        'image1.image' => 'يجب أن تكون الصورة 1 ملف صورة.',
        'image1.max' => 'يجب ألا تتجاوز الصورة 1 حجم 2 ميجابايت.',
        'image2.image' => 'يجب أن تكون الصورة 2 ملف صورة.',
        'image2.max' => 'يجب ألا تتجاوز الصورة 2 حجم 2 ميجابايت.',
        'image3.image' => 'يجب أن تكون الصورة 3 ملف صورة.',
        'image3.max' => 'يجب ألا تتجاوز الصورة 3 حجم 2 ميجابايت.',
        'image4.image' => 'يجب أن تكون الصورة 4 ملف صورة.',
        'image4.max' => 'يجب ألا تتجاوز الصورة 4 حجم 2 ميجابايت.',
        'image5.image' => 'يجب أن تكون الصورة 5 ملف صورة.',
        'image5.max' => 'يجب ألا تتجاوز الصورة 5 حجم 2 ميجابايت.',
        'image6.image' => 'يجب أن تكون الصورة 6 ملف صورة.',
        'image6.max' => 'يجب ألا تتجاوز الصورة 6 حجم 2 ميجابايت.',
        'image7.image' => 'يجب أن تكون الصورة 7 ملف صورة.',
        'image7.max' => 'يجب ألا تتجاوز الصورة 7 حجم 2 ميجابايت.',
    ]);

    if ($validator->fails()) {
        return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
    }
    $fields = $request->only([
        'category_id',
        'region_id',
        'title',
        'description',
        'price',
        'primary_image',
        'status',
        'sale_option_id',
    ]);
    try {
        $fields['user_id'] = Auth::id();
        $fields['primary_image'] = $this->ImageService->saveImage($request->file('primary_image'));
        $ad = $this->AdRepository->store($fields);
        for ($i = 1; $i <= 7; $i++) {
            $fieldName = 'image' . $i;
            if ($request->hasFile($fieldName)) {
                $image = $request->file($fieldName);
                $imagePath = $this->ImageService->saveImage($image, 'additional_image');
                $ad->images()->create(['image_url' => $imagePath]);
            }
        }
        return ApiResponseClass::sendResponse($ad, 'تم حفظ الإعلان بنجاح.');
    } catch (Exception $e) {
        return ApiResponseClass::sendError('حدث خطأ أثناء حفظ الإعلان: ' . $e->getMessage());
    }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id,Request $request)
    {
        try{
            $ad = $this->AdRepository->getByIdWithSimilarAd($id,PersonalAccessToken::findToken($request->bearerToken())->tokenable_id ?? null);
            recordViewJob::dispatch(app(ViewService::class),$ad->id, Auth::id() ?? null);
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
            if ($ad->user_id !== Auth::id()) {
                return ApiResponseClass::sendError("ليس لديك صلاحية لحدف هدا الإعلان", [], 403);
            }
            if($this->AdRepository->delete($ad->id)){
                return ApiResponseClass::sendResponse($ad, "{$ad->id} unsaved successfully.");
            }
            return ApiResponseClass::sendError("Ad with ID {$id} may not be found or not deleted. Try again.");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Ad: ' . $e->getMessage());
        }
    }

    public function verifyAd($id, Request $request){
        $validator = Validator::make($request->all(), [
            'verified'=>['required','boolean'],
        ], [
           'verified.required'=>'يجب ادخال حالة الاعلان',
           'verified.boolean' => 'يجب ان يكون اما true او false',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $ad = $this->AdRepository->getById($id);
            $ad->update(['verified' => $request->verified]);
            $message =  $request->verified ? 'ad accepted successfully' : 'ad rejected successfully';
            return ApiResponseClass::sendResponse($ad, $message);
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error deleting Ad: ' . $e->getMessage());
        }
       
    }
}
