<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Open a conversation between a client and a freelancer.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function openConversation(Request $request)
    {
        // Validate the input
        $request->validate([
            'client_id' => 'required|exists:clients,id', // Ensure the client_id exists in clients table
            'freelancer_id' => 'required|exists:freelancer_profiles,id', // Ensure the freelancer_id exists in freelancer_profiles table
        ]);

        // Check if a conversation already exists between the client and freelancer
        $existingConversation = Conversation::where('client_id', $request->client_id)
            ->where('freelancer_id', $request->freelancer_id)
            ->first();

        // If a conversation already exists, return the existing conversation
        if ($existingConversation) {
            return response()->json($existingConversation, 200); // Return the existing conversation with a 200 OK status
        }

        // Create a new conversation if no existing conversation is found
        $conversation = new Conversation();
        $conversation->client_id = $request->client_id;
        $conversation->freelancer_id = $request->freelancer_id;
        $conversation->status = 'open'; // Default status is 'open'
        $conversation->save();

        // Return the newly created conversation
        return response()->json($conversation, 201);
    }

    /**
     * Send a message within a conversation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'sender' => 'required|in:client,freelancer',
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'conversation_id' => $request->conversation_id,
            'sender' => $request->sender,
            'message' => $request->message,
        ]);

        return response()->json($message, 201);
    }
    /**
     * Get all messages for a given conversation.
     *
     * @param int $conversationId
     * @return \Illuminate\Http\Response
     */
    public function fetchMessages($conversationId)
    {
        $messages = Message::where('conversation_id', $conversationId)->get();
        return response()->json($messages);
    }
    public function getMessages($conversationId)
    {
        // Retrieve all messages for the conversation
        $messages = Message::where('conversation_id', $conversationId)->get();

        return response()->json($messages, 200);
    }
}
