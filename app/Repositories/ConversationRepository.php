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
        // Get the conversation with its relationships (messages, receiver, sender)
        $conversation = Conversation::with(['messages','receiver','sender','ad'])->findOrFail($id);
        // Add other_user to the conversation
        $conversation->other_user = ($conversation->sender_id == Auth::id()) 
        ? $conversation->receiver 
        : $conversation->sender;
        // Sort messages by ID (oldest to newest)
        $sortedMessages = $conversation->messages->sortBy('id');
    
        // Process each message to add is_sender flag and hide some fields
        $messagesWithSenderFlag = $sortedMessages->map(function ($message) {
            // Check if sender is the current user
            $message->is_sender = ($message->sender_id == Auth::id());
            // Hide created_at and updated_at fields
            $message->makeHidden(['created_at', 'updated_at']);
            return $message;
        });
    
        $this->markMessagesAsRead($id,Auth::id());
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
        // Retrieve all conversations where the user is either the sender or receiver
        $conversations = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            // Eager load sender, receiver, ad (only id and title), and lastMessage relationships
            ->with(['sender:id,name', 'receiver:id,name', 'ad:id,title', 'lastMessage'])
            ->get();
        // Process each conversation
        $conversations->map(function ($conversation) use ($userId) {
            // Determine the other user in the conversation (not the current user)
            $conversation->other_user = $conversation->sender_id == $userId ? $conversation->receiver : $conversation->sender;
            // Count unread messages where current user is the receiver
            $conversation->unread_messages_count = $conversation->messages()
                ->where('receiver_id', $userId)
                ->where('is_read', false)
                ->count();
                $conversation->has_unread = $conversation->unread_messages_count > 0;
            // the last message in a messages array 
            $conversation->messages = $conversation->lastMessage ? [$conversation->lastMessage] : [];
            // Remove the temporary lastMessage relationship as it's now in messages array
            unset($conversation->lastMessage); 
        
            return $conversation;
        });
        $conversations->unread_conversations_count = $conversations
        ->where('has_unread', true)
        ->count();
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
