<?php

namespace App\Http\Controllers\API\Dashboard;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    public function __construct(private AdRepository $AdRepository)
    {
        //
    }

    public function getAds(Request $request){
        try {
            $ads = $this->AdRepository->indexAdminDashboard($request->region_id,$request->category_id);
            return ApiResponseClass::sendResponse($ads, 'All ads retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving ads: ' . $e->getMessage());
        }
    }
}
