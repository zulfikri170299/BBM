<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
    }

    /**
     * Send message via WhatsApp Gateway (Fonnte)
     *
     * @param string $target Target phone number or group ID
     * @param string $message The message content
     * @return array
     */
    public function sendMessage($target, $message)
    {
        if (empty($this->token)) {
            Log::warning('WhatsApp Broadcast skipped: Token is not configured.');
            return ['status' => false, 'reason' => 'Token not configured'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post($this->baseUrl, [
                'target' => $target,
                'message' => $message,
                'delay' => '2',
                'countryCode' => '62', // Indonesia
            ]);

            $result = $response->json();

            if ($response->successful()) {
                Log::info('WhatsApp Broadcast sent to ' . $target, $result);
                return ['status' => true, 'response' => $result];
            }

            Log::error('WhatsApp Broadcast failed to ' . $target, $result);
            return ['status' => false, 'response' => $result];

        } catch (\Exception $e) {
            Log::error('WhatsApp Broadcast error: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }
}
