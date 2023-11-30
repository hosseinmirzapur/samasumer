<?php

use App\Http\Controllers\AgencyController;
use App\Http\Controllers\AgencyProfileController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HotelierController;
use App\Http\Controllers\HotelierProfileController;
use App\Http\Controllers\MarketerController;
use App\Http\Controllers\MarketerProfileController;
use App\Http\Controllers\MarketerReferralController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\PassengerProfileController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// Passenger Section
Route::prefix('/passenger')->group(function () {
    // Authentication
    Route::post('/enter', [PassengerController::class, 'enter']);
    Route::post('/verify-otp', [PassengerController::class, 'verifyOtp']);
    Route::post('/enter-password', [PassengerController::class, 'enterPassword']);
    Route::post('/change-password', [PassengerController::class, 'changePass']);

    // Profile
    Route::middleware(['auth:sanctum', 'type:passenger'])->prefix('/profile')->group(function () {
        Route::post('/alter', [PassengerProfileController::class, 'alter']);
        Route::get('/', [PassengerProfileController::class, 'profile']);
    });
});

// Hoteliers Section
Route::prefix('/hotelier')->group(function () {
    // Authentication
    Route::post('/enter', [HotelierController::class, 'enter']);
    Route::post('/verify-otp', [HotelierController::class, 'verifyOtp']);
    Route::post('/enter-password', [HotelierController::class, 'enterPassword']);
    Route::post('/change-password', [HotelierController::class, 'changePassword']);

    // Profile
    Route::middleware(['auth:sanctum', 'type:hotelier'])->prefix('/profile')->group(function () {
        Route::post('/alter', [HotelierProfileController::class, 'alter']);
        Route::get('/', [HotelierProfileController::class, 'profile']);
    });

    // Hotel
    Route::middleware(['auth:sanctum', 'type:hotelier'])->prefix('/hotel')->group(function () {
        Route::post('/alter', [HotelController::class, 'alter']);
        Route::get('/info', [HotelController::class, 'info']);
        Route::post('/delete-image/{image}', [HotelController::class, 'deleteImage']);
        Route::post('/set-image-as-main', [HotelController::class, 'setImageAsMain']);
        Route::post('/add-room', [RoomController::class, 'store']);
        Route::put('/alter-room', [RoomController::class, 'update']);
        Route::delete('/delete-room/{room}', [RoomController::class, 'destroy']);
    });
});

// Agency Section
Route::prefix('/agency')->group(function () {
    // Authentication
    Route::post('/enter', [AgencyController::class, 'check']);
    Route::post('/verify-otp', [AgencyController::class, 'verifyOtp']);
    Route::post('/enter-password', [AgencyController::class, 'enterPassword']);
    Route::post('/change-password', [AgencyController::class, 'changePassword']);

    // Profile
    Route::prefix('/profile')->middleware(['auth:sanctum', 'type:agency'])->group(function () {
        Route::post('/alter', [AgencyProfileController::class, 'alter']);
        Route::get('/', [AgencyProfileController::class, 'info']);
    });

    // Children
    Route::middleware(['auth:sanctum', 'type:agency'])->prefix('/children')->group(function () {
        Route::get('/', [AgencyController::class, 'childAgencies']);
        Route::post('/add', [AgencyController::class, 'addChild']);
        Route::get('/search', [AgencyController::class, 'search']);
    });
});

// Marketer Section
Route::prefix('/marketer')->group(function () {
    // Authentication
    Route::post('/enter', [MarketerController::class, 'enter']);
    Route::post('/verify-otp', [MarketerController::class, 'verifyOtp']);
    Route::post('/enter-password', [MarketerController::class, 'enterPassword']);
    Route::post('/change-password', [MarketerController::class, 'changePassword']);

    // Profile
    Route::prefix('/profile')->middleware(['auth:sanctum', 'type:marketer'])->group(function () {
        Route::get('/alter', [MarketerProfileController::class, 'alter']);
        Route::get('/', [MarketerProfileController::class, 'info']);
    });

    // Referral
    Route::prefix('/referral')->middleware(['auth:sanctum', 'type:marketer'])->group(function () {
        Route::post('/add', [MarketerReferralController::class, 'add']);
        Route::get('/list', [MarketerReferralController::class, 'list']);
    });
});

// Transactions
Route::middleware('auth:sanctum')->prefix('/transaction')->group(function () {
    Route::get('/', [TransactionController::class, 'userTxs']);
    Route::get('/search', [TransactionController::class, 'search']);
    Route::post('/check-tx', [TransactionController::class, 'checkTx']);
});

// Wallet
Route::middleware('auth:sanctum')->prefix('/wallet')->group(function () {
    Route::get('/', [WalletController::class, 'info']);
    Route::post('/deposit', [WalletController::class, 'deposit']);
    Route::post('/withdraw', [WalletController::class, 'withdraw']);
});

// Notifications
Route::middleware('auth:sanctum')->prefix('/notif')->group(function () {
    Route::get('/', [NotificationController::class, 'userNotifs']);
    Route::patch('/mark-as-read', [NotificationController::class, 'markNotifsAsRead']);
});

// Social Media
Route::get('/social-media', [SocialMediaController::class, 'info']);
Route::middleware(['auth:sanctum', 'admin'])->post('/social-media', [SocialMediaController::class, 'setInfo']);

// Logout user
Route::post('/logout', [MiscController::class, 'logout'])->middleware('auth:sanctum');

// Rate
Route::prefix('/rate')->group(function () {
    Route::post('/hotel', [RateController::class, 'rateHotel']);
});

// Facility
Route::prefix('/facility')->middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::resource('/', FacilityController::class);
});

Route::prefix('/faq')->group(function () {
    Route::resource('/', FaqController::class);
    Route::delete('/{faq}/remove-translation/{locale}', [FaqController::class, 'removeTrans']);
    Route::post('/{faq}/add-translation', [FaqController::class, 'addTrans']);
});
//Route::prefix('/about-us')->group(function () {
//
//});

/**
 * ERRORS:
 * WRONG_OTP
 * INVALID_DAT
 * MODEL_NOT_FOUND
 * NOT_FOUND
 * UNAUTHORIZED
 * OTP_TIMEOUT
 * WRONG_CREDENTIALS
 * BACKEND_ERROR
 * REFERRAL_NOT_FOUND
 * INVALID_NATIONAL_ID
 * INVALID_MOBILE
 * TX_NOT_FOUND
 * UNKNOWN_REFERRAL_CODE
 * INVALID_AGENCY_TYPE
 * USER_EXISTS
 * FILE_NOT_FOUND
 * UNSUPPORTED_LANGUAGE
 */
