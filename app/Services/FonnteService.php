<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = 'https://api.fonnte.com/send';
        $this->token = env('FONNTE_API_TOKEN');
    }

    /**
     * Send a WhatsApp message via Fonnte
     *
     * @param string $phoneNumber The recipient's phone number
     * @param string $message The message to send
     * @return bool Whether the message was sent successfully
     */
    public function sendMessage(string $phoneNumber, string $message): bool
    {
        try {
            
            $formattedNumber = $this->formatPhoneNumber($phoneNumber);
            
            $response = Http::withHeaders([
                'Authorization' => $this->token,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'target' => $formattedNumber,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $formattedNumber,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error('Fonnte API Error', [
                'status' => $response->status(),
                'response' => $response->json(),
                'phone' => $formattedNumber,
            ]);
            
            return false;
        } catch (\Exception $e) {
            Log::error('Fonnte Service Error', [
                'message' => $e->getMessage(),
                'phone' => $phoneNumber,
            ]);
            
            return false;
        }
    }

    /**
     * Format phone number to WhatsApp API requirements
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        $number = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        } else if (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }
        
        return $number;
    }
}