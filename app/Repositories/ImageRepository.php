<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Image;

class ImageRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Images with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Image::with(['ad', 'user'])->paginate(10);
    }

    /**
     * Retrieve a Image by ID.
     */
    public function getById($id): Image
    {
        return Image::with(['ad'])->findOrFail($id);
    }

    /**
     * Store a new Image.
     */
    public function store(array $data): Image
    {
        return Image::create($data);
    }

    /**
     * Update an existing Image.
     */
    public function update(array $data, $id): Image
    {
        $Image = Image::findOrFail($id);
        $Image->update($data);
        return $Image;
    }

    /**
     * Delete a Image by ID.
     */
    public function delete($id): bool
    {
        return Image::where('id', $id)->delete() > 0;
    }
    
}
