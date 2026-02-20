<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\BroadcastNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class BroadcastController extends Controller
{
    protected $whatsapp;

    public function __construct(\App\Services\WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    public function index()
    {
        return view('admin.broadcast.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'send_to_whatsapp' => 'nullable|boolean',
        ]);

        $users = User::all();
        
        // Kirim Notifikasi Sistem
        Notification::send($users, new BroadcastNotification($request->title, $request->message));

        // Kirim Pesan Chat ke Semua Personel dan Admin Satker
        $recipients = User::whereIn('role', ['personel', 'admin_satker'])->get();
        $senderId = auth()->id();
        
        // Format pesan sesuai request: [SIARAN] (Bold) -> Judul -> Isi
        // Menggunakan tag HTML karena chat view me-render dengan innerHTML
        $chatMessage = "<b>[SIARAN]</b><br>{$request->title}<br>{$request->message}";

        foreach ($recipients as $recipient) {
            \App\Models\Chat::create([
                'sender_id' => $senderId,
                'receiver_id' => $recipient->id,
                'message' => $chatMessage,
                'is_read' => false,
            ]);
        }

        // Kirim ke WhatsApp jika dicentang
        if ($request->has('send_to_whatsapp')) {
            $target = config('services.whatsapp.group_target');
            if ($target) {
                // Format pesan WhatsApp (Plain text with asterisks for bold)
                $waMessage = "*[SIARAN]*\n\n*{$request->title}*\n\n{$request->message}";
                $this->whatsapp->sendMessage($target, $waMessage);
            }
        }

        return redirect()->route('admin.broadcast.index')->with('success', 'Pesan siaran berhasil dikirim ke semua pengguna, internal chat, dan WhatsApp Group.');
    }
}
