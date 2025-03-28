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

    public function getAdsStatisticsByYear($year)
    {
        $statistics = $this->AdRepository->getAdsStatisticsByYear($year);
        $monthNames = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];
    
        $monthlyAds = [];
        foreach ($monthNames as $num => $name) {
            $monthlyAds[$name] = 0; 
        }
    
        foreach ($statistics as $stat) {
            $monthName = $monthNames[$stat->month];
            $monthlyAds[$monthName] = $stat->ads_count;
        }
        $result=[
            'year' => $year,
            'monthly_ads' => $monthlyAds
        ];
        ApiResponseClass::sendResponse($result,'statistics retrieved successfully.');
    }
}
