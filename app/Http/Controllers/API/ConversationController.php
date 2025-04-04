<?php

namespace App\Http\Controllers\api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\TestPusherEvent;
use App\Classes\ApiResponseClass;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MessageRepository;
use App\Repositories\ConversationRepository;
use Illuminate\Support\Facades\Validator;

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
            $unreadCount = $conversations->where('has_unread', true)->count();
    
            $responseData = $conversations->toArray();
            $responseData['unread_conversations_count'] = $unreadCount;
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
        $validator = Validator::make($request->all(), [
            'ad_id' => ['required',Rule::exists('ads','id')],
            'receiver_id' => ['required',Rule::exists('users','id')],
        ]);
        if ($validator->fails()) {
           return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        try {
            $fields=$request->only(['ad_id','receiver_id']);
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
        $validator = Validator::make($request->all(), [
            'message_text' => ['required', 'string'],
            'conversation_id' => ['required', Rule::exists('conversations', 'id')],
            'attachment' => ['nullable', 'image'],
        ], [
           'message_text.required'=>'يجب إدخال نص الرساله',
           'message_text.string'=>'يجب أن تكون الرساله نصاً',
           'conversation_id.required'=>'يجب إدخال رقم المحادثة',
        ]);
    
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }

        try {
            $fields=$request->only(['message_text','conversation_id','attachment']);
            $conversation = $this->ConversationRepository->getById($fields['conversation_id']);

            $userId = Auth::id();
            if ($conversation->sender_id != $userId && $conversation->receiver_id != $userId) {
                return ApiResponseClass::sendError('Unauthorized: You are not part of this conversation.', 403);
            }
            $fields['receiver_id'] = ($conversation->sender_id == $userId) ? $conversation->receiver_id : $conversation->sender_id;
            $fields['sender_id'] = $userId;

            $message = $this->MessageRepository->store($fields);
            event( new TestPusherEvent($fields['message_text'],$fields['receiver_id'],$fields['conversation_id'],$fields['sender_id'],$message->id));
            return ApiResponseClass::sendResponse($message, "Message sent successfully",201);
            } catch (Exception $e) {
                return ApiResponseClass::sendError('Error sending message: ' . $e->getMessage());
            }
    }

    public function checkConversationExists(Request $request){
        $validator = Validator::make($request->all(), [
            'ad_id' => ['required',Rule::exists('ads','id')],
        ]);
        if ($validator->fails()) {
            return ApiResponseClass::sendValidationError($validator->errors()->first(), $validator->errors());
        }
        $fields=$request->only(['ad_id']);
        $Conversation=$this->ConversationRepository->checkConversationExists($fields['ad_id']);
        if($Conversation){
            return ApiResponseClass::sendResponse($Conversation, "the conversation is exists",201);
        }
        else{
            return ApiResponseClass::sendResponse($Conversation, "not found");
        }
    }

    /**
    * Mark all unread messages as read in a conversation.
    */
    public function markMessagesAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => ['required', Rule::exists('conversations', 'id')],
        ],[
            'conversation_id.required'=>'يجب كتابة رقم المحادثه',
            'conversation_id.exists'=>'المحادثة غير مسجله في النظام'
        ]);
        if ($validator->fails()) {
           return ApiResponseClass::sendValidationError($validator->errors()->first(),$validator->errors());
        }
        $fields=$request->only(['conversation_id']);
        try {
            $userId = Auth::id();
            $conversation = $this->ConversationRepository->getById($fields['conversation_id']);
            if ($conversation->sender_id != $userId && $conversation->receiver_id != $userId) {
                return ApiResponseClass::sendError('Unauthorized: You are not part of this conversation.', 403);
            }
            $this->ConversationRepository->markMessagesAsRead($fields['conversation_id'], $userId);
            return ApiResponseClass::sendResponse(null, 'All messages marked as read successfully.');
        } catch (Exception $e) {
            return ApiResponseClass::sendError('Error marking messages as read: ' . $e->getMessage());
        }
    }


}
