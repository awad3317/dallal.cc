<?php

namespace App\Http\Controllers\API\Auth;

use Laravolt\Avatar\Avatar;
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

class userAuthController extends Controller
{
    public function __construct(private UserRepository $UserRepository,private OtpService $otpService,private AvatarService $AvatarService)
    {
        //
    }

    public function register(Request $request)
    {
        $fields=$request->validate([
            'name'=>['required','string','max:100'],
            'email' => ['required','email',Rule::unique('users','email')],
            'password' => ['required','string','min:6','confirmed',],
            'phone_number' => ['required','string','min:10','max:15',],
        ]);

        // Create the avatar using the service
        // $fields['image'] = $this->AvatarService->createAvatar($fields['name']);

        // Store the new user using the UserRepository
        $user=$this->UserRepository->store($fields);

        // Generate a random OTP and prepare it for sending
        $otp=$this->otpService->generateOTP($user->email);

        // Send an email with the OTP code to the user's email address
        // SendOtpEmailJob::dispatch($user->email, $otp);
        
        return ApiResponseClass::sendResponse($user,'OTP sent to ' . $user->email);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'identifier' => ['required'],
            'password' => ['required','string'],
        ]);
        $user=$this->UserRepository->findByUsernameOrEmail($credentials['identifier']);
        if (!$user) {
            return ApiResponseClass::sendError('Unauthorized', ['error' => 'Invalid credentials'], 401);
        }
        if (!$user->email_verified) {
            // Generate a random OTP and prepare it for sending
            $otp=$this->otpService->generateOTP($user->email);

            // Send an email with the OTP code to the user's email address
            // SendOtpEmailJob::dispatch($user->email, $otp);
            return ApiResponseClass::sendError('Unauthorized', ['error' => 'Email not verified. An OTP has been sent to '.$user->email]);
        }

        // Check if the user exists and if the password is correct
        if($user && Hash::check($credentials['password'], $user->password)){
            // Log in the user
            Auth::login($user);

            $this->UserRepository->update(['last_login'=>now()],$user->id);
            // Create a new token for the user
            $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;
            return ApiResponseClass::sendResponse(['token' => $token, 'user' => $user], 'User logged in successfully');
        }
        return ApiResponseClass::sendError('Unauthorized', ['error' => 'Invalid credentials']);
        
    }

    public function logout(Request $request)
    { 
        $user = Auth::user();
        $user->tokens()->delete(); 
        return ApiResponseClass::sendResponse(null, 'Logged out successfully');
    }

}
