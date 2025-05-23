<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Ad;
use App\Jobs\recordViewJob;
use Illuminate\Http\Request;
use App\Services\ViewService;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private AdRepository $AdRepository,private ImageService $ImageService,private ViewService $ViewService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $ads = $this->AdRepository->index($request->region_slug,$request->category_slug,$request->category_child_slug, $request->latitude, $request->longitude);
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
        // Rate limiting: 5 new ads per 2 hours per user
        $maxAttempts = 5;
        $decaySeconds = 7200; // 2 hours = 7200 seconds
        $key = 'ad-submission:' . Auth::id(); 

        $executed = RateLimiter::attempt(
            $key,
            $maxAttempts,
            function () {},
            $decaySeconds
        );

        if (!$executed) {
            $seconds = RateLimiter::availableIn($key);
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
        
            $message = sprintf(
                'لقد تجاوزت الحد المسموح لنشر الإعلانات (%d إعلان كل %d ساعات). يرجى المحاولة مرة أخرى بعد %d ساعة و %d دقيقة.',
                $maxAttempts,
                ($decaySeconds / 3600),
                $hours,
                $minutes
            );
        
            return ApiResponseClass::sendError($message, null, 429);
        }
    $validator = Validator::make($request->all(), [
        'category_id' => ['required', Rule::exists('categories', 'id')->where(function ($query){return $query->where('parent_id', '!=', null);})],
        'region_id' => ['required', Rule::exists('regions', 'id')->where(function ($query){return $query->where('parent_id', '!=', null);})],
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
    public function show($slug,Request $request)
    {
        try{
            $ad = $this->AdRepository->getBySlugWithSimilarAd($slug,PersonalAccessToken::findToken($request->bearerToken())->tokenable_id ?? null);
            if($ad == false){
                return ApiResponseClass::sendError('This ad has been rejected and cannot be viewed');
            }
            // recordViewJob::dispatch(app(ViewService::class),$ad->id, Auth::id() ?? null);
            $this->ViewService->recordView($ad->id,Auth::id() ?? null);
            return ApiResponseClass::sendResponse($ad, " data getted  successfully");
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Ad: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id){

        try {
            $Ad=$this->AdRepository->edit($id);
            // Check if the authenticated user owns the ad
            if ($Ad->user_id !== Auth::id()) {
                // return ApiResponseClass::sendError("ليس لديك صلاحية لتعديل هذا الإعلان", [], 403);
                return ApiResponseClass::sendResponse(null,'ليس لديك صلاحية لتعديل هدا الاعلان');
            }
            return ApiResponseClass::sendResponse($Ad,'data getted  successfully');
        }catch(Exception $e){
            return ApiResponseClass::sendError('Error returned Ad: ' . $e->getMessage());
        }
       
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => ['sometimes', 'required', Rule::exists('categories', 'id')],
            'region_id' => ['sometimes', 'required', Rule::exists('regions', 'id')],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'primary_image' => ['sometimes', Rule::when($request->hasFile('primary_image'),['image','max:2048']),Rule::when(is_string($request->primary_image),'string')],
            'status' => ['sometimes', 'required', 'in:جديد,مستعمل'],
            'sale_option_id' => ['sometimes', 'required', Rule::exists('sale_options', 'id')],
        ],[
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
            'primary_image.image' => 'يجب أن تكون الصورة الرئيسية ملف صورة.',
            'primary_image.max' => 'يجب ألا تتجاوز الصورة الرئيسية 2 ميجابايت.',
            'status.required' => 'حقل الحالة مطلوب.',
            'status.in' => 'الحالة يجب أن تكون إما "جديد" أو "مستعمل".',
            'sale_option_id.required' => 'حقل خيار البيع مطلوب.',
            'sale_option_id.exists' => 'خيار البيع المحدد غير موجود.',
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $ad = $this->AdRepository->getById($id);
            // Check if the authenticated user owns the ad
            if ($ad->user_id !== Auth::id()) {
                return ApiResponseClass::sendError("ليس لديك صلاحية لتعديل هذا الإعلان", [], 403);
            }
            $fields = $request->only(['category_id','region_id','title','description','price','status','sale_option_id']);
            if ($request->hasFile('primary_image')) {
                // Delete old primary image
                $this->ImageService->deleteImage($ad->primary_image);
                // Save new primary image
                $fields['primary_image'] = $this->ImageService->saveImage($request->file('primary_image'));
            }
            // // Update the ad
            $updatedAd = $this->AdRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($fields, 'تم تحديث الإعلان بنجاح.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ أثناء تحديث الإعلان: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ad=$this->AdRepository->getById($id);
            if ($ad->user_id !== Auth::id() && !Auth::user()->has_role('admin')) {
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

    public function updatePrimaryImage(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'primary_image' => ['required', 'image', 'max:2048'],
        ],[
            'primary_image.required' =>'يجب رفع صورة',
            'primary_image.image' =>'يجب أن يكون الملف صورة',
            'primary_image.image' =>'يجب أن يكون حجم الصورة 2 MB'
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        try {
            $ad = $this->AdRepository->getById($id);
            // Check if the authenticated user owns the ad
            if ($ad->user_id !== Auth::id()) {
                return ApiResponseClass::sendError("ليس لديك صلاحية لتعديل هذه الصورة", [], 403);
            }
            if ($request->hasFile('primary_image')) {
                // Delete old primary image
                $this->ImageService->deleteImage($ad->primary_image);
                // Save new primary image
                $fields['primary_image'] = $this->ImageService->saveImage($request->file('primary_image'));
            }
            // Update the ad
            $updatedAd = $this->AdRepository->update($fields,$id);
            return ApiResponseClass::sendResponse($updatedAd, 'تم تحديث الصورة بنجاح.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطأ أثناء تحديث الصورة: ' . $e->getMessage());
        }
    }

    public function nearbyAds(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'sometimes|numeric|min:1|max:100' 
        ]);
        
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
    
        try {
            $ads = Ad::select('ads.*')
    ->join('regions', 'ads.region_id', '=', 'regions.id')
    ->whereNotNull('regions.latitude')
    ->whereNotNull('regions.longitude')
    ->selectRaw(
        '(6371 * acos(cos(radians(?)) * cos(radians(regions.latitude)) * 
        cos(radians(regions.longitude) - radians(?)) + 
        sin(radians(?)) * sin(radians(regions.latitude)))) AS distance',
        [
            $request->latitude, 
            $request->longitude, 
            $request->latitude
        ]
    )
    ->whereRaw('(6371 * acos(cos(radians(?)) * cos(radians(regions.latitude)) * 
        cos(radians(regions.longitude) - radians(?)) + 
        sin(radians(?)) * sin(radians(regions.latitude)))) < ?',
        [
            $request->latitude,
            $request->longitude,
            $request->latitude,
            $request->radius ?? 10 
        ]
    )->with(['category.parent', 'region.parent', 'saleOption'])->where(function($query) {
        $query->whereNull('verified')->orWhere('verified', true);
    })
    ->withMax('bids', 'amount')
    ->orderBy('distance')
    ->paginate(12);
    
            return ApiResponseClass::sendResponse($ads, 'تم جلب الإعلانات بنجاح');
    
        }catch (Exception $e) {
            return ApiResponseClass::sendError('حدث خطا     : ' . $e->getMessage());
        }
    }
}
