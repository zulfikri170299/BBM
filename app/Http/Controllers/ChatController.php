<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua user kecuali diri sendiri
        // Sort by unread messages first, then by name
        $users = User::with('satker')
                    ->where('id', '!=', $user->id)
                    ->withCount(['sentChats as unread_count' => function ($query) use ($user) {
                        $query->where('receiver_id', $user->id)
                              ->where('is_read', false);
                    }])
                    ->orderByDesc('unread_count')
                    ->orderBy('name')
                    ->get();
                    
        return view('chat.index', compact('users'));
    }

    public function show(User $receiver)
    {
        $sender = Auth::user();
        $receiver->load('satker');
        
        // Mark chats as read
        Chat::where('sender_id', $receiver->id)
            ->where('receiver_id', $sender->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return view('chat.show', compact('receiver'));
    }

    public function getMessages(User $receiver)
    {
        $sender = Auth::user();
        
        $messages = Chat::where(function($q) use ($sender, $receiver) {
                        $q->where('sender_id', $sender->id)
                          ->where('receiver_id', $receiver->id);
                    })
                    ->orWhere(function($q) use ($sender, $receiver) {
                        $q->where('sender_id', $receiver->id)
                          ->where('receiver_id', $sender->id);
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
                    
        return response()->json($messages);
    }

    public function store(Request $request, User $receiver)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'message' => $request->message,
        ]);

        return response()->json($chat);
    }
    public function unreadCount()
    {
        $user = Auth::user();
        $count = Chat::where('receiver_id', $user->id)
                     ->where('is_read', false)
                     ->count();
                     
        return response()->json(['count' => $count]);
    }

    public function destroy(Chat $chat)
    {
        if ($chat->sender_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat->delete();
        return response()->json(['success' => true]);
    }
}
