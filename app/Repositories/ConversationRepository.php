<?php

namespace App\Repositories;
use App\Interfaces\RepositoriesInterface;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

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
        return Conversation::with(['ad','sender','receiver'])->paginate(10);
    }

    public function getById($id): Conversation
    {
        
        $conversation = Conversation::with(['messages','receiver','sender'])->findOrFail($id);
    
    
        $sortedMessages = $conversation->messages->sortBy('id');
    
        
        $messagesWithSenderFlag = $sortedMessages->map(function ($message) {
            $message->is_sender = ($message->sender_id == Auth::id());
            $message->makeHidden(['created_at', 'updated_at']);
            return $message;
        });
    
        
        $conversation->setAttribute('messages', $messagesWithSenderFlag);
    
        return $conversation;
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

    public function getUserConversations($userId)
    {
        // Retrieve conversations where the user is either the sender or the receiver
        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'messages','ad'])
            ->get();
        // Add an `other_user` object to each conversation
        $conversations->map(function ($conversation) use ($userId) {
            // Determine the other user in the conversation
            $conversation->other_user = $conversation->sender_id == $userId? $conversation->receiver: $conversation->sender;
            return $conversation;
        });
        return $conversations;
    }

    public function checkConversationExists($adId)
    {
        $senderId= Auth::id();
        $Conversation=Conversation::where('sender_id', $senderId)
        ->where('ad_id', $adId)
        ->first();
        if($Conversation){
            return $this->getById($Conversation->id);
        }
        else{
            return false;
        }
    }
    /**
    * Mark all unread messages as read for a specific conversation and user.
    */
    public function markMessagesAsRead($conversationId, $userId)
    {
        Message::where('conversation_id', $conversationId)
        ->where('receiver_id', $userId)
        ->where('is_read', false)
        ->update(['is_read' => true]);
    }
    
}
