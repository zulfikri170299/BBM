<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $token;
    protected $baseUrl = 'https://api.fonnte.com/send';

    public function __construct($token = null)
    {
        $this->token = $token ?: (\App\Models\Setting::where('key', 'whatsapp_token')->first()->value ?? config('services.whatsapp.token'));
    }

    /**
     * Get available groups from Fonnte
     *
     * @return array
     */
    public function getGroups()
    {
        if (empty($this->token)) {
            return ['status' => false, 'reason' => 'Token not configured'];
        }

        try {
            // First try to get existing groups
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post('https://api.fonnte.com/get-whatsapp-group');

            $result = $response->json();

            Log::info('WhatsApp getGroups response', ['status' => $response->status(), 'body' => $result]);

            if ($response->successful()) {
                return ['status' => true, 'data' => $result];
            }

            return ['status' => false, 'response' => $result];
        } catch (\Exception $e) {
            Log::error('WhatsApp getGroups exception: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
    }

    /**
     * Sync/Fetch groups from WhatsApp to Fonnte
     */
    public function syncGroups()
    {
        if (empty($this->token)) {
            return ['status' => false, 'reason' => 'Token not configured'];
        }

        try {
            // This API tells Fonnte to scan WhatsApp for new groups
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->asForm()->post('https://api.fonnte.com/fetch-group');

            $result = $response->json();
            Log::info('WhatsApp syncGroups response', ['status' => $response->status(), 'body' => $result]);

            return ['status' => $response->successful(), 'response' => $result];
        } catch (\Exception $e) {
            Log::error('WhatsApp syncGroups exception: ' . $e->getMessage());
            return ['status' => false, 'reason' => $e->getMessage()];
        }
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
