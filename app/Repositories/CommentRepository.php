<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Comment;

class CommentRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Comments with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Comment::paginate(10);
    }

    /**
     * Retrieve a Comment by ID.
     */
    public function getById($id): Comment
    {
        return Comment::findOrFail($id);
    }

    /**
     * Store a new Comment.
     */
    public function store(array $data): Comment
    {
        return Comment::create($data);
    }

    /**
     * Update an existing Comment.
     */
    public function update(array $data, $id): Comment
    {
        $Comment = Comment::findOrFail($id);
        $Comment->update($data);
        return $Comment;
    }

    /**
     * Delete a Comment by ID.
     */
    public function delete($id): bool
    {
        return Comment::where('id', $id)->delete() > 0;
    }

}
