<?php

namespace App\Http\Controllers\API\Auth;

use Exception;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Jobs\SendOtpEmailJob;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class forgetPasswordController extends Controller
{
    public function __construct(private OtpService $otpService,private UserRepository $UserRepository)
    {
        //
    }
    
    public function forgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', Rule::exists('users', 'email')],
        ],[
            'email.required' => 'يجب إدخال البريد الإلكتروني',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح',
            'email.exists' => 'البريد الإلكتروني غير مسجل في النظام',
        ]);
        if ($validator->fails()) {
           return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['email']);
            $otp = $this->otpService->generateOTP($fields['email'],'forgetPassword');
            SendOtpEmailJob::dispatch($fields['email'], $otp);
            return ApiResponseClass::sendResponse(null, 'تم إرسال رمز التحقق إلى: ' . $fields['email']);
        } catch (Exception $e) {
            return ApiResponseClass::sendError(null, 'فشل في إرسال رمز التحقق. ' . $e->getMessage());
        }
    }

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email',Rule::exists('users', 'email')],
            'otp' => ['required', 'numeric'],
            'new_password' => ['required', 'string', 'min:8'],
        ], [
            'email.required' => 'يجب إدخال البريد الإلكتروني',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح',
            'email.exists' => 'البريد الإلكتروني غير مسجل في النظام',
            'otp.required' => 'يجب إدخال رمز التحقق',
            'otp.numeric' => 'يجب أن يكون رمز التحقق رقماً',
            'new_password.required' => 'يجب إدخال كلمة المرور الجديدة',
            'new_password.min' => 'يجب أن تكون كلمة المرور الجديدة على الأقل 8 أحرف',
        ]);
    
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        $fields = $request->only(['email', 'otp', 'new_password']);
        if ($this->otpService->verifyOTP($fields['email'], $fields['otp'])) {
            $user = $this->UserRepository->findByEmail($fields['email']);
            $this->UserRepository->update(['password' =>$fields['new_password']], $user->id);
            return ApiResponseClass::sendResponse(null, 'تم تحديث كلمة المرور بنجاح.');
        }
        return ApiResponseClass::sendError('رمز التحقق غير صالح أو منتهي الصلاحية', [], 400);
    }
}
