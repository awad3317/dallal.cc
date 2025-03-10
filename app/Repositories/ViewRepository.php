<?php

namespace App\Repositories;
use App\Models\View;
use App\Interfaces\RepositoriesInterface;

class ViewRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Views with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return View::paginate(10);
    }

    /**
     * Retrieve a View by ID.
     */
    public function getById($id): View
    {
        return View::findOrFail($id);
    }

    /**
     * Store a new View.
     */
    public function store(array $data): View
    {
        return View::create($data);
    }

    /**
     * Update an existing View.
     */
    public function update(array $data, $id): View
    {
        $View = View::findOrFail($id);
        $View->update($data);
        return $View;
    }

    /**
     * Delete a View by ID.
     */
    public function delete($id): bool
    {
        return View::where('id', $id)->delete() > 0;
    }

    public function hasUserViewedAd($adId,$userId){
        if ($userId) {
            return View::where('ad_id', $adId)
                ->where('user_id', $userId)
                ->exists();
        } else {
            return View::where('ad_id', $adId)
                ->where(function ($query) {
                    $query->where('session_id', session()->getId())
                          ->orWhere('ip_address', request()->ip());
                })
                ->exists();
        }
    }

}
