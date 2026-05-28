<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class OtpService
{
    /**
     * Generate OTP code (6 digits)
     */
    public function generateOtp($identifier)
    {
        $otp = rand(100000, 999999);
        // Save to cache for 5 minutes
        Cache::put('otp_' . $identifier, $otp, 300);
        return $otp;
    }

    /**
     * Send OTP via Email
     */
    public function sendOtp($email, $otp)
    {
        try {
            Mail::to($email)->send(new OtpMail($otp));
            Log::info("OTP dikirim ke Email {$email}");
            return true;
        } catch (\Exception $e) {
            Log::error("Gagal mengirim OTP ke Email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp($identifier, $otp)
    {
        $cachedOtp = Cache::get('otp_' . $identifier);

        if ($cachedOtp && $cachedOtp == $otp) {
            Cache::forget('otp_' . $identifier); // Invalidate after use
            return true;
        }

        return false;
    }
}
