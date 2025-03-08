<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Like;

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
        return Like::create($data);
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

}
