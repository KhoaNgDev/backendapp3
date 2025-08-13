<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Frontend\LoginRequest;
use App\Http\Requests\API\Frontend\SendOtpRequest;
use App\Models\User;
use App\Services\BrevoService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoginController extends Controller
{
    protected $brevo;

    public function __construct(BrevoService $brevo)
    {
        $this->brevo = $brevo;
    }
    public function sendOtp(SendOtpRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();
        Log::info('sendOtp called', ['phone' => $request->phone]);
        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại.'], 404);
        }

        if ($user->otp_code && Carbon::now()->lt($user->otp_expires_at)) {
            return response()->json([
                'message' => 'OTP đã gửi, vui lòng sử dụng mã cũ hoặc chờ hết hạn để gửi lại.'
            ], 429);
        }

        $otp = rand(100000, 999999);

        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(5),
            'otp_attempts' => 0,
        ]);

        // Gửi qua Brevo API
        $subject = 'Mã OTP đăng nhập';
        $htmlContent = "<p>Mã OTP của bạn là: <strong>{$otp}</strong></p>";
        try {
            $this->brevo->sendEmail(
                $user->email,
                $user->name ?? 'Khách hàng',
                $subject,
                $htmlContent
            );
        } catch (\Exception $e) {
            Log::error('Gửi OTP qua Brevo thất bại', [
                'error' => $e->getMessage(),
                'email' => $user->email
            ]);

            return response()->json([
                'message' => 'Không thể gửi OTP, vui lòng thử lại sau.'
            ], 500);
        }



        Log::info('OTP sent', ['user_id' => $user->id, 'phone' => $user->phone]);

        return response()->json(['message' => 'OTP đã được gửi qua email.']);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json(['message' => 'Tài khoản không tồn tại.'], 404);
        }

        // Password login
        if ($request->filled('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Sai mật khẩu.'], 401);
            }
        }

        // OTP login
        if ($request->filled('otp')) {
            if (!$user->otp_code) {
                return response()->json(['message' => 'OTP chưa được tạo hoặc đã dùng.'], 401);
            }

            if (Carbon::now()->gt($user->otp_expires_at)) {
                return response()->json(['message' => 'OTP đã hết hạn, vui lòng gửi lại.'], 401);
            }

            if ($user->otp_code !== $request->otp) {
                $user->increment('otp_attempts');

                if ($user->otp_attempts >= 3) {
                    $user->update([
                        'otp_code' => null,
                        'otp_expires_at' => null,
                        'otp_attempts' => 0,
                        'otp_context' => 'login'
                    ]);
                    return response()->json([
                        'message' => 'Bạn đã nhập sai OTP quá 3 lần, vui lòng gửi lại OTP mới.'
                    ], 401);
                }

                return response()->json(['message' => 'Mã OTP không chính xác.'], 401);
            }

            // OTP đúng
            $user->update([
                'otp_code' => null,
                'otp_expires_at' => null,
                'otp_attempts' => 0,
                'otp_context' => 'login'
            ]);
        }

        $token = $user->createToken('vue-client')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        Log::info('Logout success', ['user_id' => $request->user()->id]);

        return response()->json(['message' => 'Đã đăng xuất.']);
    }
}
