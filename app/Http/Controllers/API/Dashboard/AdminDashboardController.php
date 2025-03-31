<?php

namespace App\Http\Controllers\API\Dashboard;

use Exception;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use App\Repositories\AdRepository;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function __construct(private AdRepository $AdRepository,private UserRepository $UserRepository)
    {
        //
    }

    /**
     * Get all ads (Admin only)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAds(Request $request){
        try {
            // if (!Auth::user()->has_role('admin')) {
            //     return ApiResponseClass::sendError('Unauthorized', 403);
            // }
            $ads = $this->AdRepository->indexAdminDashboard($request->region_id,$request->category_id);
            return ApiResponseClass::sendResponse($ads, 'All ads retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving ads: ' . $e->getMessage());
        }
    }

    /**
     * Get statistics by year (Admin only)
     *
     * @param int $year
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatisticsByYear($year)
    {
        $adsStatistics = $this->AdRepository->getAdsStatisticsByYear($year);
        $usersStatistics = $this->UserRepository->getUsersStatisticsByYear($year);
    
        $monthNames = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ];
    
        $monthlyAds = array_fill_keys(array_values($monthNames), 0);
        $monthlyUsers = array_fill_keys(array_values($monthNames), 0);
    
        foreach ($adsStatistics as $stat) {
            $monthNum = str_pad($stat->month, 2, '0', STR_PAD_LEFT);
            $monthName = $monthNames[$monthNum] ?? 'Unknown';
            $monthlyAds[$monthName] = $stat->ads_count;
        }
    
        foreach ($usersStatistics as $stat) {
            $monthNum = str_pad($stat->month, 2, '0', STR_PAD_LEFT);
            $monthName = $monthNames[$monthNum] ?? 'Unknown';
            $monthlyUsers[$monthName] = $stat->users_count;
        }
    
        $result = [
            'year' => $year,
            'monthly_ads' => $monthlyAds,
            'monthly_users' => $monthlyUsers
        ];
    
        return ApiResponseClass::sendResponse($result, 'Statistics retrieved successfully.');
    }
}
