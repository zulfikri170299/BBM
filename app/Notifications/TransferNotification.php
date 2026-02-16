<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TransferNotification extends Notification
{
    use Queueable;

    protected $sender;
    protected $amount;
    protected $description;

    /**
     * Create a new notification instance.
     */
    public function __construct($sender, $amount, $description = null)
    {
        $this->sender = $sender;
        $this->amount = $amount;
        $this->description = $description;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Transfer Saldo Masuk',
            'message' => 'Anda menerima transfer saldo sebesar ' . number_format($this->amount, 0, ',', '.') . ' Liter dari ' . $this->sender->nama,
            'amount' => $this->amount,
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->nama,
            'description' => $this->description,
            'type' => 'transfer_received'
        ];
    }
}
