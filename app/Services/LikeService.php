<?php


namespace App\Services;

use App\Repositories\AdRepository;
use App\Repositories\LikeRepository;
use Illuminate\Support\Facades\Auth;

class LikeService
{
    /**
     * Create a new class instance.
     */
    public function __construct(private LikeRepository $LikeRepository,private AdRepository $AdRepository)
    {
        //
    }
    public function likeAd($adId)
    {
        $like= $this->LikeRepository->likeAd($adId);
        if($like->wasRecentlyCreated){
            $this->AdRepository->incrementLikes($adId);
        }
        return $like;
    }
    public function unlikeAd($adId)
    {
        $like = $this->LikeRepository->unlikeAd($adId);
        if($like){
            $this->AdRepository->decrementLikes($adId);
        }
        return $like;
    }
}