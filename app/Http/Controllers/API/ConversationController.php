<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MessageRepository;
use App\Repositories\ConversationRepository;

class ConversationController extends Controller
{
     /**
     * Create a new class instance.
     */
    public function __construct(private ConversationRepository $ConversationRepository,private MessageRepository $MessageRepository)
    {
        //
    }
   
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $userId = Auth::id();
            $conversations = $this->ConversationRepository->getUserConversations($userId);
            return ApiResponseClass::sendResponse($conversations, 'All conversations retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error retrieving conversations: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'ad_id' => ['required',Rule::exists('ads','id')],
            'receiver_id' => ['required',Rule::exists('users','id')],
        ]);
        try {
            $fields['sender_id']=Auth::id();
            $conversation = $this->ConversationRepository->store($fields);
            return ApiResponseClass::sendResponse($conversation,'conversation saved successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error save conversation: ' . $e->getMessage());
            
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $conversation = $this->ConversationRepository->getById($id);
            return ApiResponseClass::sendResponse($conversation, " data getted successfully");
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error returned conversation: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sendMessage(Request $request)
{
    $fields = $request->validate([
        'message_text' => ['required', 'string'],
        'conversation_id' => ['required', Rule::exists('conversations', 'id')],
        'attachment' => ['nullable', 'image'],
    ]);

    try {
        $conversation = $this->ConversationRepository->getById($fields['conversation_id']);

        $userId = Auth::id();
        if ($conversation->sender_id != $userId && $conversation->receiver_id != $userId) {
            return ApiResponseClass::sendError('Unauthorized: You are not part of this conversation.', 403);
        }
        $fields['receiver_id'] = ($conversation->sender_id == $userId) ? $conversation->receiver_id : $conversation->sender_id;
        $fields['sender_id'] = $userId;

        $message = $this->MessageRepository->store($fields);
        return ApiResponseClass::sendResponse($message, "Message sent successfully");
    } catch (Exception $e) {
        return ApiResponseClass::sendError('Error sending message: ' . $e->getMessage());
    }
}
}
