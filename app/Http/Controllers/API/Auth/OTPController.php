<?php

namespace App\Http\Controllers\api\auth;

use Exception;
use App\Services\OtpService;
use Illuminate\Http\Request;
use App\Jobs\SendOtpEmailJob;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class OTPController extends Controller
{
    public function __construct(private OtpService $otpService,private UserRepository $UserRepository)
    {
        //
    }

    public function resendOTP(Request $request) {
        $fields=$request->validate([
            'email' => ['required','email',Rule::exists('users','email')],
        ]);
        try {
            $otp=$this->otpService->generateOTP($fields['email']);
            SendOtpEmailJob::dispatch($fields['email'], $otp);
            return ApiResponseClass::sendResponse(null,'OTP resent to ' . $fields['email']);
        } catch (Exception $e) {
            return ApiResponseClass::sendError(null,'Failed to resend OTP. ' . $e->getMessage());
        }
        
    }

    public function verifyOtpAndLogin(Request $request) {
        $fields=$request->validate([
            'email' => ['required','email'],
            'otp' => ['required','numeric'],
        ]);
        // Verify the provided OTP using the OTP service
        if($this->otpService->verifyOTP($fields['email'],$fields['otp'])){
            $user=$this->UserRepository->findByEmail($fields['email']);

            // Update the user record to mark email as verified and set the last login time
            $this->UserRepository->update(['email_verified'=>true,'last_login'=>now()],$user->id);
            Auth::login($user);
            
            // Create a new authentication token for the user
            $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
            return ApiResponseClass::sendResponse(['token' => $token, 'user' => $user], 'User logged in successfully');
        }
        return ApiResponseClass::sendError('Invalid or expired OTP.',[],400);
    }

    // public function verifyOtp(Request $request) {
    //     $fields=$request->validate([
    //         'email' => ['required','email'],
    //         'otp' => ['required','numeric'],
    //     ]);
    //     if($this->otpService->verifyOTP($fields['email'],$fields['otp'])){
    //         $user=$this->UserRepository->findByEmail($fields['email']);
    //         $this->UserRepository->update(['email_verified'=>true],$user->id);
    //         return ApiResponseClass::sendResponse(null,'Your email has been verified successfully.');
    //     }
    //     return ApiResponseClass::sendError(null,'The provided OTP is invalid or has expired.',400);
    // }
}
