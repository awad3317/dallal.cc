<?php


namespace App\Services;

use App\Repositories\AdRepository;
use App\Repositories\ViewRepository;

class ViewService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private ViewRepository $ViewRepository,private AdRepository $AdRepository)
    {
        //
    }
    public function recordView($adId,$userId=null)
    {
        $hasViewed = $this->ViewRepository->hasUserViewedAd($adId,$userId);

        if (!$hasViewed) {
            $this->ViewRepository->store([
                'ad_id' => $adId,
                'user_id' => $userId,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
            ]);
            
            $this->AdRepository->incrementViews($adId);
        }
    }
}