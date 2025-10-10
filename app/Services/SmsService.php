<?php

namespace App\Services;

use App\Models\SmsLog;
use Exception;

class SmsService
{
    protected $provider;
    protected $apiKey;
    protected $apiSecret;
    protected $senderId;

    public function __construct()
    {
        $this->provider = config('services.sms.provider', 'twilio');
        $this->apiKey = config('services.sms.api_key');
        $this->apiSecret = config('services.sms.api_secret');
        $this->senderId = config('services.sms.sender_id');
    }

    /**
     * Send SMS to a phone number
     */
    public function send($phoneNumber, $message, $userId = null)
    {
        $log = SmsLog::create([
            'user_id' => $userId,
            'phone_number' => $phoneNumber,
            'message' => $message,
            'status' => 'pending',
            'sms_provider' => $this->provider,
        ]);

        try {
            switch ($this->provider) {
                case 'twilio':
                    $response = $this->sendViaTwilio($phoneNumber, $message);
                    break;
                case 'nexmo':
                    $response = $this->sendViaNexmo($phoneNumber, $message);
                    break;
                case 'local':
                    $response = $this->sendViaLocalGateway($phoneNumber, $message);
                    break;
                default:
                    throw new Exception('Invalid SMS provider');
            }

            $log->update([
                'status' => 'sent',
                'message_id' => $response['message_id'] ?? null,
                'response' => json_encode($response),
                'sent_at' => now(),
            ]);

            return ['success' => true, 'log_id' => $log->id];
        } catch (Exception $e) {
            $log->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Send SMS to multiple phone numbers
     */
    public function sendBulk(array $phoneNumbers, $message, $userId = null)
    {
        $results = [];
        foreach ($phoneNumbers as $phoneNumber) {
            $results[] = $this->send($phoneNumber, $message, $userId);
        }
        return $results;
    }

    /**
     * Send via Twilio
     */
    protected function sendViaTwilio($phoneNumber, $message)
    {
        // Twilio implementation
        // Requires: composer require twilio/sdk
        /*
        $twilio = new \Twilio\Rest\Client($this->apiKey, $this->apiSecret);
        
        $twilioMessage = $twilio->messages->create(
            $phoneNumber,
            [
                'from' => $this->senderId,
                'body' => $message
            ]
        );
        
        return [
            'message_id' => $twilioMessage->sid,
            'status' => $twilioMessage->status,
        ];
        */

        // Mock response for demonstration
        return [
            'message_id' => 'SM' . uniqid(),
            'status' => 'sent',
        ];
    }

    /**
     * Send via Nexmo/Vonage
     */
    protected function sendViaNexmo($phoneNumber, $message)
    {
        // Nexmo implementation
        // Requires: composer require vonage/client
        /*
        $basic = new \Vonage\Client\Credentials\Basic($this->apiKey, $this->apiSecret);
        $client = new \Vonage\Client($basic);
        
        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS($phoneNumber, $this->senderId, $message)
        );
        
        $current = $response->current();
        
        return [
            'message_id' => $current->getMessageId(),
            'status' => $current->getStatus(),
        ];
        */

        // Mock response for demonstration
        return [
            'message_id' => 'NX' . uniqid(),
            'status' => 'sent',
        ];
    }

    /**
     * Send via Local SMS Gateway (Bangladesh)
     */
    protected function sendViaLocalGateway($phoneNumber, $message)
    {
        // Bangladesh SMS Gateway implementation
        // Example: SSL Wireless, Grameenphone, Robi, etc.
        
        $apiUrl = config('services.sms.gateway_url');
        
        if (!$apiUrl) {
            throw new Exception('SMS Gateway URL not configured');
        }

        $params = [
            'user' => $this->apiKey,
            'pass' => $this->apiSecret,
            'sender' => $this->senderId,
            'phone' => $phoneNumber,
            'message' => $message,
        ];

        // Send HTTP request
        $url = $apiUrl . '?' . http_build_query($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception('SMS Gateway returned HTTP ' . $httpCode);
        }

        return [
            'message_id' => 'LG' . uniqid(),
            'status' => 'sent',
            'response' => $response,
        ];
    }

    /**
     * Check SMS delivery status
     */
    public function checkStatus($messageId)
    {
        $log = SmsLog::where('message_id', $messageId)->first();
        
        if (!$log) {
            return ['success' => false, 'error' => 'SMS log not found'];
        }

        // Check status with provider
        // Implementation depends on provider

        return [
            'success' => true,
            'status' => $log->status,
            'sent_at' => $log->sent_at,
        ];
    }

    /**
     * Get SMS statistics
     */
    public function getStatistics($startDate = null, $endDate = null)
    {
        $query = SmsLog::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        return [
            'total' => $query->count(),
            'sent' => $query->where('status', 'sent')->count(),
            'failed' => $query->where('status', 'failed')->count(),
            'pending' => $query->where('status', 'pending')->count(),
            'delivered' => $query->where('status', 'delivered')->count(),
        ];
    }
}
