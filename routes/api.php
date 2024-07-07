<?php

use App\Http\Controllers\Api\DeliveryMan\Beta\AuthController;
use App\Http\Controllers\Api\DeliveryMan\Beta\ParcelController;
use App\Http\Controllers\Api\DeliveryMan\V10\AuthController as V10AuthController;
use App\Http\Controllers\Api\DeliveryMan\V10\ParcelController as V10ParcelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v10')->group(function() {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login-otp', [V10AuthController::class, 'loginOtp']);
        Route::post('login', [V10AuthController::class, 'login']);
        Route::post('forgot-password-otp', [V10AuthController::class, 'forgotPasswordOtp']);
        Route::post('forgot-password', [V10AuthController::class, 'forgotPasswordPost']);
    });

    Route::middleware(['jwt.verify','CheckApiKey'])->group(function () {
        Route::get('profile',[V10AuthController::class,'profile']);
        Route::post('statistics',[V10AuthController::class,'statistics']);
        Route::get('logout',[V10AuthController::class, 'logout']);
        Route::post('change-password',[V10AuthController::class, 'changePassword']);
        Route::post('update-profile',[V10AuthController::class, 'updateProfile']);
        Route::get('notification', [V10AuthController::class, 'notification']);
        Route::get('notification-update/{id}', [V10AuthController::class, 'updateNotification']);
        Route::get('privacy-policy', [V10AuthController::class, 'privacyPolicy']);
        Route::post('language', [V10AuthController::class, 'language']);
        Route::post('update-profile-image',[V10AuthController::class, 'updateProfileImage']);
        Route::get('payment-logs',[V10AuthController::class, 'paymentLogs']);
        Route::post('cash-deposits',[V10AuthController::class, 'cashDeposits']);
        Route::post('pending-amount',[V10AuthController::class, 'pendingAmount']);
        Route::post('earning',[V10AuthController::class, 'earning']);
        Route::get('my-pickup',[V10ParcelController::class, 'myPickup']);
        Route::get('pending-pickup',[V10ParcelController::class, 'pickupPending']);
        Route::get('completed-pickup',[V10ParcelController::class, 'pickupCompleted']);
        Route::get('cancel-reason',[V10ParcelController::class, 'cancelReason']);
        Route::get('processing-delivery',[V10ParcelController::class, 'processingDelivery']);
        Route::get('my-re-scheduled-delivery',[V10ParcelController::class, 'myReScheduledDelivery']);
        Route::get('pending-delivery',[V10ParcelController::class, 'deliveryPending']);
        Route::get('completed-delivery',[V10ParcelController::class, 'deliveryCompleted']);
        Route::get('cancel-delivery',[V10ParcelController::class, 'deliveryCancelled']);
        Route::post('otp-verify',[V10ParcelController::class, 'parcelDeliveryConfirm']);
        Route::post('reschedule-pickup',[V10ParcelController::class, 'reshedulePickup']);
        Route::post('reschedule-delivery',[V10ParcelController::class, 'resheduleDelivery']);
        Route::post('parcel-cancel',[V10ParcelController::class, 'cancel']);
        Route::post('delivered',[V10ParcelController::class, 'delivery']);
        Route::get('parcel-details/{id}',[V10ParcelController::class, 'parcelDetails']);
        Route::get('my-pickup-merchants',[V10ParcelController::class, 'myPickupMerchants']);
        Route::post('pickup-received',[V10ParcelController::class, 'pickupReceived']);
    });
});





