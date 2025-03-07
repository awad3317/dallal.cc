<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\api\RegionController;
use App\Http\Controllers\api\auth\OTPController;
use App\Http\Controllers\api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\SaleOptionController;
use App\Http\Controllers\api\Auth\userAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout',[userAuthController::class,'logout']);
    Route::apiResource('/region',RegionController::class);
    Route::get('/regions/parents', [RegionController::class,'getParents']);
    Route::get('/regions/{id}/children', [RegionController::class,'getChildren']);
    Route::apiResource('/category',CategoryController::class);
    Route::get('/categories/parents', [CategoryController::class,'getParents']);
    Route::get('/categories/{id}/children', [CategoryController::class,'getChildren']);
    Route::apiResource('/ad',AdController::class)->only(['store']);
    Route::apiResource('/saleOption',SaleOptionController::class);
    Route::apiResource('/bid',BidController::class);
    Route::apiResource('/favorite',FavoriteController::class)->except(['show','update']);
    Route::apiResource('/image',ImageController::class)->except(['show','index']);
    
    
});
Route::post('/register',[userAuthController::class,'register']);
Route::post('/login',[userAuthController::class,'login']);
Route::post('/verifyOtpAndLogin',[OTPController::class,'verifyOtpAndLogin']);
Route::post('/resendOTP',[OTPController::class,'resendOTP']);
Route::apiResource('/ad',AdController::class)->except(['store']);


