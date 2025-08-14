<?php
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Frontend\ExportRepairPdfController;
use App\Http\Controllers\API\Frontend\RepairLookupController;
use App\Http\Controllers\API\Frontend\RepairRatingController;
use Illuminate\Support\Facades\Route;

Route::prefix('client')
    ->controller(LoginController::class)
    ->middleware('throttle:global')
    ->group(function () {
        Route::post('login', 'login')->name('client.login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
        Route::post('send-otp', 'sendOtp')->middleware('throttle:otp-login')->name('client.send-otp');
    });

Route::prefix('repair')
    ->controller(RepairLookupController::class)
    ->middleware('throttle:otp-lookup')
    ->group(function () {
        Route::post('request-otp', 'requestOtpForLookup')->name('repair.request-otp');
        Route::post('verify-otp', 'verifyOtpForLookup')->name('repair.verify-otp');
    });

Route::middleware(['auth:sanctum', 'throttle:auth'])
    ->group(function () {
        Route::get('/vehicles/{vehicle}/repairs/export', [ExportRepairPdfController::class, 'export']);
        Route::prefix('repair')
            ->controller(RepairLookupController::class)
            ->group(function () {
                Route::post('/repair-search', 'search')->name('repair-search');
                
            });

        Route::prefix('feedback')
            ->controller(RepairRatingController::class)
            ->group(function () {
                Route::post('/submit-rating', 'store')->name('submit-rating');
                Route::delete('{id}', 'destroy')->name('feedback.delete');
            });
    });
Route::fallback(function () {
    return response()->json([
        'message' => 'API không tồn tại hoặc phương thức không hợp lệ.'
    ], 404);
});
