<?php

namespace App\Repositories;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\RepositoriesInterface;

class LikeRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Likes with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Like::paginate(10);
    }

    /**
     * Retrieve a Like by ID.
     */
    public function getById($id): Like
    {
        return Like::findOrFail($id);
    }

    /**
     * Store a new Like.
     */
    public function store(array $data): Like
    {
        return Like::firstOrCreate($data);
    }

    /**
     * Update an existing Like.
     */
    public function update(array $data, $id): Like
    {
        $Like = Like::findOrFail($id);
        $Like->update($data);
        return $Like;
    }

    /**
     * Delete a Like by ID.
     */
    public function delete($id): bool
    {
        return Like::where('id', $id)->delete() > 0;
    }

    public function likeAd($adId)
    {
        $userId = Auth::id();

        $like = Like::firstOrCreate([
            'user_id' => $userId,
            'ad_id' => $adId,
        ]);

        return $like;
    }

    public function unlikeAd($adId)
    {
        $userId = Auth::id();

        $like = Like::where('user_id', $userId)
            ->where('ad_id', $adId)
            ->first();

        if ($like) {
            $like->delete();
            return true;
        }

        return false;
    }

}
