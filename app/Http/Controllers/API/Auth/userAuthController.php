<?php

namespace App\Http\Controllers\API\Auth;

use App\Mail\OtpMail;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Jobs\SendOtpEmailJob;
use App\Services\AvatarService;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class userAuthController extends Controller
{
    public function __construct(private UserRepository $UserRepository,private OtpService $otpService,private AvatarService $AvatarService)
    {
        //
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>['required','string','max:100'],
            'email' => ['required','email',Rule::unique('users','email')],
            'password' => ['required','string','min:6','confirmed',],
            'phone_number' => ['required','string','min:9','max:15',],
        ],[
            'name.required' => 'الاسم مطلوب.',
            'email.required' => 'يجب إدخال البريد الإلكتروني',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقًا.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 6 أحرف على الأقل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'phone_number.required' => 'رقم الهاتف مطلوب.',
            'phone_number.min' => 'رقم الهاتف يجب أن يحتوي على 9 أحرف على الأقل.',
            'phone_number.max' => 'رقم الهاتف لا يمكن أن يتجاوز 15 حرفًا.',

        ]);
        if ($validator->fails()) {
           return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        $fields = $request->only(['name', 'email', 'password', 'phone_number']);
       
        // Create the avatar using the service
        $fields['image'] = $this->AvatarService->createAvatar($fields['name']);

        // Store the new user using the UserRepository
        $user=$this->UserRepository->store($fields);

        // Generate a random OTP and prepare it for sending
        $otp=$this->otpService->generateOTP($user->email,'account_creation');

        // Send an email with the OTP code to the user's email address
        // SendOtpEmailJob::dispatch($user->email, $otp);
        Mail::to($user->email)->send(new OtpMail($otp));
        
        return ApiResponseClass::sendResponse($user,'تم إرسال رمز التحقق الى البريد الإلكتروني :'. $user->email);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => ['required'],
            'password' => ['required','string'],
        ],[
            'identifier.required'=>'يجب كتابة البريد الإلكتروني',
            'password.required'=>'يجب كتابة كلمة المرور',
        ]);
        if ($validator->fails()) {
           return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        $credentials=$request->only(['identifier','password']);
        $user=$this->UserRepository->findByUsernameOrEmail($credentials['identifier']);
        if (!$user) {
            return ApiResponseClass::sendError('Unauthorized', ['error' => 'البيانات غير صحيحه'], 401);
        }
        if (!$user->email_verified) {
            // Generate a random OTP and prepare it for sending
            $otp=$this->otpService->generateOTP($user->email,'account_creation');

            // Send an email with the OTP code to the user's email address
            // SendOtpEmailJob::dispatch($user->email, $otp);
            Mail::to($user->email)->send(new OtpMail($otp));
            return ApiResponseClass::sendError('Forbidden', ['error' => 'البريد الإلكتروني غير محقق. تم إرسال رمز التحقق'.$user->email],403);
        }

        // Check if the user exists and if the password is correct
        if($user && Hash::check($credentials['password'], $user->password)){
            if($user->is_banned){
                return ApiResponseClass::sendError('الحساب محظور',null,403);
            }
            // Log in the user
            Auth::login($user);

            $this->UserRepository->update(['last_login'=>now()],$user->id);
            // Create a new token for the user
            $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
            $user->token=$token;
            return ApiResponseClass::sendResponse(['user' => $user], 'تم تسجيل دخول بنجاح');
        }
        return ApiResponseClass::sendError('Unauthorized', ['error' => 'البيانات غير صحيحه'],401);
        
    }

    public function logout(Request $request)
    { 
        $user = Auth::user();
        $user->tokens()->delete(); 
        return ApiResponseClass::sendResponse(null, 'تم تسجيل الخروج بنجاح');
    }

}
