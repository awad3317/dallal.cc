<?php

use Illuminate\Http\Request;
use App\Events\TestPusherEvent;
use App\Events\sentMessageEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AdController;
use App\Http\Controllers\API\BidController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\Auth\OTPController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\FavoriteController;
use App\Http\Controllers\API\Auth\RoleController;
use App\Http\Controllers\API\SaleOptionController;
use App\Http\Controllers\API\ConversationController;
use App\Http\Controllers\API\Auth\userAuthController;
use App\Http\Controllers\API\Auth\PermissionController;
use App\Http\Controllers\API\Auth\forgetPasswordController;
use App\Http\Controllers\API\Dashboard\UserDashboardController;
use Illuminate\Support\Facades\Broadcast;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Broadcast::routes(['middleware'=>['auth:sanctum']]);
Route::post('/pusher/auth',function (Request $request){
    return Broadcast::auth($request);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout',[userAuthController::class,'logout']);
        //      Dashboard for User      //
    Route::get('/getUserData',[UserDashboardController::class,'getUserData']);
    Route::get('/getUserAds',[UserDashboardController::class,'getUserAds']);
    Route::get('/getUserFavoriteAds',[UserDashboardController::class,'getUserFavoriteAds']);

    Route::apiResource('/region',RegionController::class)->except(['index']);
    Route::apiResource('/category',CategoryController::class)->except(['inedx']);
    Route::apiResource('/comment',CommentController::class)->except(['show','update']);
    Route::apiResource('user', UserController::class)->except(['store']);
    Route::apiResource('/ad',AdController::class)->except(['index','show']);
    Route::apiResource('/saleOption',SaleOptionController::class)->except(['index']);
    Route::apiResource('/bid',BidController::class)->except(['index']);
    Route::apiResource('/image',ImageController::class)->except(['show','index']);
    Route::apiResource('/role',RoleController::class)->except(['update']);
    Route::apiResource('/conversation',ConversationController::class)->except(['update','destroy']);
    Route::post('/sendMessage',[ConversationController::class,'sendMessage']);
    Route::post('/checkConversationExists',[ConversationController::class,'checkConversationExists']);
    Route::get('/permission',[PermissionController::class,'index']);
    Route::apiResource('/favorite',FavoriteController::class)->except(['show','update']);
    Route::post('/like',[LikeController::class,'store']);
    Route::delete('/like/{ad_id}',[LikeController::class,'destroy']);

    Route::post('/conversations/mark-as-read', [ConversationController::class, 'markMessagesAsRead']);
    
});
    //           Auth Route          //
Route::post('/register',[userAuthController::class,'register']);
Route::post('/login',[userAuthController::class,'login']);
Route::post('/verifyOtpAndLogin',[OTPController::class,'verifyOtpAndLogin']);
Route::post('/resendOTP',[OTPController::class,'resendOTP']);
    //             Regions           //
Route::get('/regions/parents', [RegionController::class,'getParents']);
Route::get('/regions/{id}/children', [RegionController::class,'getChildren']);
Route::get('/region',[RegionController::class,'index']);
    //             Category           //
Route::get('/categories/parents', [CategoryController::class,'getParents']);
Route::get('/categories/{id}/children', [CategoryController::class,'getChildren']);
Route::get('/category',[CategoryController::class,'index']);
    //             Forget Password           //
Route::post('/forgetPassword', [forgetPasswordController::class,'forgetPassword']);
Route::post('/resetPassword', [forgetPasswordController::class,'resetPassword']);


Route::get('/ad',[AdController::class,'index']);
Route::get('/ad/{id}',[AdController::class,'show']);
Route::get('/saleOption',[SaleOptionController::class,'index']);
Route::get('/bid',[BidController::class,'index']);

// Route::get('/test',function (){
//     event( new TestPusherEvent('hi i am awad')); 
//     return 'done';
// });










