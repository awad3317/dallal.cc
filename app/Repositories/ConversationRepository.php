<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Conversation;

class ConversationRepository implements RepositoriesInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Retrieve all Conversations with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Conversation::with(['ad', 'user'])->paginate(10);
    }

    /**
     * Retrieve a Conversation by ID.
     */
    public function getById($id): Conversation
    {
        return Conversation::with(['ad', 'user'])->findOrFail($id);
    }

    /**
     * Store a new Conversation.
     */
    public function store(array $data): Conversation
    {
        return Conversation::create($data);
    }

    /**
     * Update an existing Conversation.
     */
    public function update(array $data, $id): Conversation
    {
        $Conversation = Conversation::findOrFail($id);
        $Conversation->update($data);
        return $Conversation;
    }

    /**
     * Delete a Conversation by ID.
     */
    public function delete($id): bool
    {
        return Conversation::where('id', $id)->delete() > 0;
    }
    
}
