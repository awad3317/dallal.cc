<?php

namespace App\Http\Controllers\API\Dashboard;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class AdminDashboardController extends Controller
{
    public function __construct(private AdRepository $AdRepository,private UserRepository $UserRepository)
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

    public function getStatisticsByYear($year)
    {
    $adsStatistics = $this->AdRepository->getAdsStatisticsByYear($year);
    $usersStatistics = $this->UserRepository->getUsersStatisticsByYear($year);
    
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
    $monthlyUsers = [];
    
    foreach ($monthNames as $num => $name) {
        $monthlyAds[$name] = 0;
        $monthlyUsers[$name] = 0;
    }
    
    foreach ($adsStatistics as $stat) {
        $monthName = $monthNames[$stat->month];
        $monthlyAds[$monthName] = $stat->ads_count;
    }
    
    foreach ($usersStatistics as $stat) {
        $monthName = $monthNames[$stat->month];
        $monthlyUsers[$name] = $stat->users_count;
    }
    
    $result = [
        'year' => $year,
        'monthly_ads' => $monthlyAds,
        'monthly_users' => $monthlyUsers
    ];
    
    return ApiResponseClass::sendResponse($result, 'Statistics retrieved successfully.');
    }
}
