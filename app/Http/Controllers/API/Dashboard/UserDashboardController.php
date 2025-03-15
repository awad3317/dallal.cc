<?php

namespace App\Http\Controllers\API\Dashboard;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private UserRepository $UserRepository)
    {
        //
    }

    public function getUserData()
    {
        try {
            $user = $this->UserRepository->getById(Auth::id()); 
            return ApiResponseClass::sendResponse($user, 'user retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving user: ' . $e->getMessage());
        }
    }

    public function getUserAds()
    {
        try {
            $user = $this->UserRepository->getById(Auth::id());
            $ads = $user->ads()->with(['category', 'region', 'saleOption'])->withMax('bids', 'amount')->paginate(10); 
            return ApiResponseClass::sendResponse($ads, 'ads retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving ads: ' . $e->getMessage());
        }
    }

    public function getUserFavoriteAds()
    {
        try {
            $user = $this->UserRepository->getById(Auth::id());
            $favorites = $user->favorites()->with(['category', 'region', 'saleOption'])->withMax('bids', 'amount')->paginate(10);
            // $favorites = $user->likes()->with(['category', 'region', 'saleOption'])->withMax('bids', 'amount')->paginate(10);  
            return ApiResponseClass::sendResponse($favorites, 'favorites retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving favorites: ' . $e->getMessage());
        }
    }

}
