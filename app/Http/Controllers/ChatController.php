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
        $usersQuery = User::with('satker')
                    ->where('id', '!=', $user->id);

        $personelAccessControl = \App\Models\Setting::where('key', 'personel_access_control')->value('value') ?? '1';
        if ($personelAccessControl == '0') {
            $usersQuery->where('role', '!=', 'personel');
        }

        $users = $usersQuery->withCount(['sentChats as unread_count' => function ($query) use ($user) {
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

    public function getLatestUnread(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['has_unread' => false]);
        }

        $latestChat = Chat::with(['sender.satker'])
            ->where('receiver_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->first();

        if (!$latestChat || !$latestChat->sender) {
            return response()->json(['has_unread' => false]);
        }

        $sender = $latestChat->sender;
        $messageRaw = strip_tags($latestChat->message);
        $messageSnippet = \Illuminate\Support\Str::limit($messageRaw, 90, '...');

        return response()->json([
            'has_unread' => true,
            'message' => [
                'id' => $latestChat->id,
                'sender_id' => $sender->id,
                'sender_name' => $sender->name,
                'sender_role' => $sender->role_label,
                'sender_satker' => $sender->satker ? $sender->satker->nama_satker : null,
                'sender_initial' => strtoupper(substr($sender->name, 0, 1)),
                'has_photo' => $sender->has_profile_photo,
                'avatar_url' => $sender->has_profile_photo ? asset('storage/' . $sender->profile_photo_path) : null,
                'text_full' => $messageRaw,
                'text_snippet' => $messageSnippet,
                'chat_url' => route('chat.show', $sender->id),
                'created_at_human' => $latestChat->created_at ? $latestChat->created_at->diffForHumans() : 'Baru saja',
                'created_at_time' => $latestChat->created_at ? $latestChat->created_at->format('H:i') : '',
            ]
        ]);
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
