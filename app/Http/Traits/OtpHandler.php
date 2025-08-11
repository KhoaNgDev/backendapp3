<?php

namespace App\Http\Traits;

use App\Mail\OtpMail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

trait OtpHandler
{
    public function sendOtpTo(User $user, string $context = 'login'): void
    {
        if (
            $user->otp_code &&
            $user->otp_context === $context &&
            now()->lt($user->otp_expires_at)
        ) {
            throw new Exception('OTP đã được gửi. Vui lòng kiểm tra email hoặc chờ mã hết hạn để gửi lại.');
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'otp_attempts' => 0,
            'otp_context' => $context,
        ]);

        Mail::to($user->email)->send(new OtpMail($otp, $context));
    }

    public function verifyOtp(User $user, string $otp, string $context = 'login'): void
    {
        if (!$user->otp_code || $user->otp_context !== $context) {
            throw new Exception('Vui lòng chờ hệ thống tạo lại OTP.');
        }

        if (now()->gt($user->otp_expires_at)) {
            $this->clearOtp($user);
            throw new Exception('OTP đã hết hạn.');
        }

        if ($user->otp_code !== $otp) {
            $user->increment('otp_attempts');

            if ($user->otp_attempts >= 3) {
                $this->clearOtp($user);
                throw new Exception('Nhập sai OTP quá 3 lần. Hệ thống đã xoá mã cũ.');
            }

            throw new Exception('Mã OTP không chính xác.');
        }

        $this->clearOtp($user);
    }

    protected function clearOtp(User $user): void
    {
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_context' => null,
        ]);
    }
}
