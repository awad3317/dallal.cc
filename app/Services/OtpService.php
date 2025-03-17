<?php

namespace App\Services;

use App\Repositories\OtpRepository;

class OtpService
{
    public function __construct(private OtpRepository $OtpRepository)
    {
        
    }

    public function generateOTP($email,$purpose='account_creation')
    {
        $existingOtp=$this->OtpRepository->findByEmail($email);
        if($existingOtp){
            $this->OtpRepository->delete($existingOtp->id);
        }
        $otp = rand(100000, 999999); 
        $expiresAt = now()->addMinutes(10); 
        $data=[
            'email' => $email,
            'code' => $otp,
            'expires_at' => $expiresAt,
            'purpose' => $purpose,
        ];
        $this->OtpRepository->store($data);
        return $otp; 
    }

    public function verifyOTP($email, $code)
    {
        $otp = $this->OtpRepository->verifyOTP($email, $code);

        if ($otp) {
            $otp->is_used = true;
            $otp->save();
            return true;
        }

        return false; 
    }
}