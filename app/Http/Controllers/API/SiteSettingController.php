<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\SiteSettingRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class SiteSettingController extends Controller
{
    /**
     * Create a new class instance.
     */
    public function __construct(private SiteSettingRepository $SiteSettingRepository,private ImageService $ImageService)
    {
        //
    } 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $siteSettings= Cache::remember('site_settings', now()->addHours(24), function () {
                return $this->SiteSettingRepository->index()->first();
            });
            return ApiResponseClass::sendResponse($siteSettings, 'All SiteSettings retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving SiteSettings: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request)
    {
        if (!Auth::user()->has_role('admin')) {
            return ApiResponseClass::sendError('Unauthorized', 403);
        }
        $validator = Validator::make($request->all(), [
            'site_name' => ['required','string'],
            'meta_description' => ['required','string'],
            'meta_keywords' => ['required','string'],
            'email' => ['required','email'],
            'phone' => ['required','string'],
            'address' => ['required','string'],
            'is_maintenance' =>['required', 'boolean'],
            'working_hours'=>['required','string'],
            'maintenance_message' =>  ['required','string'],
            'logo'=> ['sometimes', Rule::when($request->hasFile('logo'),['mimes:svg,jpeg,png,jpg,gif','max:2048']),Rule::when(is_string($request->logo),'string')],
            'favicon'=>['sometimes', Rule::when($request->hasFile('favicon'),['mimes:svg','max:2048']),Rule::when(is_string($request->favicon),'string')]
        ],[
            'site_name.required' => 'حقل اسم الموقع مطلوب.',
            'site_name.string' => 'يجب أن يكون اسم الموقع نصياً.',
            'meta_description.required' => 'حقل وصف الميتا مطلوب.',
            'meta_description.string' => 'يجب أن يكون وصف الميتا نصياً.',
            'meta_keywords.required' => 'حقل الكلمات المفتاحية مطلوب.',
            'meta_keywords.string' => 'يجب أن تكون الكلمات المفتاحية نصية.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'phone.required' => 'حقل الهاتف مطلوب.',
            'phone.string' => 'يجب أن يكون الهاتف نصياً.',
            'address.required' => 'حقل العنوان مطلوب.',
            'address.string' => 'يجب أن يكون العنوان نصياً.',
            'is_maintenance.required' => 'حقل وضع الصيانة مطلوب.',
            'is_maintenance.boolean' => 'يجب أن يكون وضع الصيانة نعم أو لا.',
            'working_hours.required' => 'حقل ساعات العمل مطلوب.',
            'working_hours.string' => 'يجب أن تكون ساعات العمل نصية.',
            'maintenance_message.required' => 'حقل رسالة الصيانة مطلوب.',
            'maintenance_message.string' => 'يجب أن تكون رسالة الصيانة نصية.',
            'logo.mimes' => 'يجب أن يكون الملف من نوع: svg, jpeg, png, jpg, gif.',
            'logo.max' => 'يجب ألا يتجاوز حجم الملف 2 ميجابايت.',
            'favicon.mimes' => 'يجب أن يكون الملف من نوع svg فقط.',
            'favicon.max' => 'يجب ألا يتجاوز حجم الملف 2 ميجابايت.',

        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $siteSetting = SiteSetting::firstOrNew();
            $fields=$request->only(['site_name','meta_description','meta_keywords','email','phone','address','is_maintenance','maintenance_message','working_hours']);
            if ($request->hasFile('logo')) {
                // Delete old primary image
                $this->ImageService->deleteImage($siteSetting->logo_path);
                // Save new primary image
                $fields['logo_path'] = $this->ImageService->saveImage($request->file('logo','Site_images'));
            }
            if ($request->hasFile('favicon')) {
                // Delete old primary image
                $this->ImageService->deleteImage($siteSetting->favicon_path);
                // Save new primary image
                $fields['favicon_path'] = $this->ImageService->saveImage($request->file('favicon','Site_images'));
            }
            $siteSetting=$this->SiteSettingRepository->update($fields,null);
            Cache::forget('site_settings');
            return ApiResponseClass::sendResponse($fields, 'تم تحديث الإعدادات بنجاح.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error updated SiteSetting: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
