<?php

namespace App\Jobs;

use App\Services\ViewService;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class recordViewJob implements ShouldQueue
{
    use Queueable;
    private ViewService $ViewService;
    protected $adId;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($adId,$userId,ViewService $viewService)
    {
        $this->ViewService = $viewService;
        $this->adId=$adId;
        $this->userId=$userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
           $this->ViewService->recordView($this->adId,$this->userId);
        } catch (\Exception $e) {
            Log::error('Failed to record view: ' . $e->getMessage());
        }
    }
}
