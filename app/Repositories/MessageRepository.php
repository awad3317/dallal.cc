<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Message;

class MessageRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Messages with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Message::paginate(10);
    }

    /**
     * Retrieve a Message by ID.
     */
    public function getById($id): Message
    {
        return Message::findOrFail($id);
    }

    /**
     * Store a new Message.
     */
    public function store(array $data): Message
    {
        return Message::create($data);
    }

    /**
     * Update an existing Message.
     */
    public function update(array $data, $id): Message
    {
        $Message = Message::findOrFail($id);
        $Message->update($data);
        return $Message;
    }

    /**
     * Delete a Message by ID.
     */
    public function delete($id): bool
    {
        return Message::where('id', $id)->delete() > 0;
    }
    
}
