<?php

namespace App\Jobs;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtpEmailJob implements ShouldQueue
{
    use Queueable;

    protected $email;
    protected $otp;

    /**
     * Create a new job instance.
     */
    public function __construct($email,$otp)
    {
        $this->otp=$otp;
        $this->email=$email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->email)->send(new OtpMail($this->otp));
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email: ' . $e->getMessage());
        }
    }
}
