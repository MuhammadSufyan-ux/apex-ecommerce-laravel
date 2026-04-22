<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public static function send($data)
    {
        $type = $data['type']; // email, sms, whatsapp
        $recipient = $data['recipient'];
        $message = $data['message'];
        $event = $data['event'];
        $recipientType = $data['recipient_type'] ?? 'customer';

        // Check if enabled in settings
        $isEnabled = Setting::getValue('notify_'.$type.'_enabled', 0);
        if (!$isEnabled) return;

        try {
            // Logic to send would go here (linking to Mailables, SMS APIs, WhatsApp APIs)
            // For now, we simulate success and log it
            
            NotificationLog::create([
                'type' => $type,
                'event' => $event,
                'recipient_type' => $recipientType,
                'recipient' => $recipient,
                'message' => $message,
                'status' => 'sent'
            ]);

            return true;
        } catch (\Exception $e) {
            NotificationLog::create([
                'type' => $type,
                'event' => $event,
                'recipient_type' => $recipientType,
                'recipient' => $recipient,
                'message' => $message,
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
            Log::error("Notification failed: " . $e->getMessage());
            return false;
        }
    }
}
