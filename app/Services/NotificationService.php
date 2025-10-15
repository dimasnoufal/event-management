<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FcmNotification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private Messaging $messaging;

    // public function __construct()
    // {
    //     $this->messaging = (new Factory)
    //         ->withServiceAccount(env('FIREBASE_CREDENTIALS')) 
    //         ->createMessaging();
    // }

    public function __construct()
    {
        // ✅ Windows compatible path
        $credentialsPath = storage_path('app/firebase/firebase-credentials.json');
        
        // ✅ Debug untuk Windows
        Log::info('Firebase credentials path: ' . $credentialsPath);
        Log::info('File exists: ' . (file_exists($credentialsPath) ? 'YES' : 'NO'));
        
        if (!file_exists($credentialsPath)) {
            Log::error('Firebase credentials file not found. Expected location: ' . $credentialsPath);
            throw new \Exception('Firebase credentials file not found at: ' . $credentialsPath);
        }

        $this->messaging = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->createMessaging();
    }

    /**
     * Kirim ke 1 token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = []): void
    {
        $message = CloudMessage::new()
            ->withNotification(FcmNotification::create($title, $body))
            ->withData(array_map('strval', $data))
            ->toToken($token);

        $this->messaging->send($message);

    }

    /**
     * Kirim ke banyak token (multicast)
     * Kreait: sendMulticast($message, array $tokens)
     * Batas FCM: 500 token per request → kita chunk.
     */
    public function sendToMany(array $tokens, string $title, string $body, array $data = []): void
    {
        $tokens = array_values(array_filter(array_unique($tokens)));
        if (empty($tokens)) return;

        $baseMessage = CloudMessage::new()
            ->withNotification(FcmNotification::create($title, $body))
            ->withData(array_map('strval', $data));

        foreach (array_chunk($tokens, 500) as $batch) {
            $this->messaging->sendMulticast($baseMessage, $batch);
            // optional: periksa report hasilnya
            // $report = $this->messaging->sendMulticast($baseMessage, $batch);
            // \Log::info('FCM multicast report', ['success' => $report->successes()->count(), 'fail' => $report->failures()->count()]);
        }
    }
}
