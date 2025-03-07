<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Bid;

class BidRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Bids with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Bid::with(['ad', 'user'])->paginate(10);
    }

    /**
     * Retrieve a Bid by ID.
     */
    public function getById($id): Bid
    {
        return Bid::with(['ad', 'user'])->findOrFail($id);
    }

    /**
     * Store a new Bid.
     */
    public function store(array $data): Bid
    {
        return Bid::create($data);
    }

    /**
     * Update an existing Bid.
     */
    public function update(array $data, $id): Bid
    {
        $Bid = Bid::findOrFail($id);
        $Bid->update($data);
        return $Bid;
    }

    /**
     * Delete a Bid by ID.
     */
    public function delete($id): bool
    {
        return Bid::where('id', $id)->delete() > 0;
    }
    
}
