<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::get('activation/{email}/{code}', [AuthController::class, 'activation']);
Route::get('password-forgot', [AuthController::class, 'forgotPassword'])->name('password.forgot');
Route::post('password-forgot', [AuthController::class, 'forgot'])->name('forgot.password-email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('password/reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset-password.post');
Route::get('user/verified/{verify}', [AuthController::class, 'verified'])->name('user.verified');
Route::get('whatsapp/verify/{verify}', [AuthController::class, 'whatsappVerify'])->name('whatsapp.verify');

Route::post('whatsapp/otp-send', [AuthController::class, 'whatsappOtp'])->name('whatsapp.otp.send');
Route::post('whatsapp/otp-confirm', [AuthController::class, 'whatsappOtpConfirm'])->name('whatsapp.otp.confirm');




Route::group(['prefix' => localeRoutePrefix()], function () {
    Route::middleware('guest')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store'])->name('signup.store');

        Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('postlogin');

        // Route::get('/verify', [AuthenticatedSessionController::class, 'verifyEmail'])->name('verify');
        // Route::post('verify', [AuthenticatedSessionController::class, 'verifyEmailStore'])->name('verify.email.store');

        Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
        Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

        Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
        Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    });

    Route::middleware('auth')->group(function () {
        Route::get('verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');
    });
});
