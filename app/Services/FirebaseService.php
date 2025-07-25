<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        try {
            $this->messaging = (new Factory)
                ->withServiceAccount(config('firebase.credentials'))
                ->createMessaging();
        } catch (\Exception $e) {
            Log::error('Firebase initialization failed: ' . $e->getMessage());
            $this->messaging = null;
        }
    }

    /**
     * Send notification to single device
     */
    public function sendNotification($deviceToken, $title, $body, $data = [])
    {
        if (!$this->messaging) {
            throw new \Exception('Firebase messaging not initialized');
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification)
                ->withData(array_merge(['timestamp' => now()->toISOString()], $data));

            return $this->messaging->send($message);
        } catch (\Exception $e) {
            Log::error('Firebase notification failed', [
                'token' => $deviceToken,
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send notification to multiple devices
     */
    public function sendNotificationToMultiple(array $deviceTokens, $title, $body, $data = [])
    {
        if (!$this->messaging || empty($deviceTokens)) {
            return [];
        }

        $notification = Notification::create($title, $body);
        $messages = [];

        foreach ($deviceTokens as $token) {
            $messages[] = CloudMessage::withTarget('token', $token)
                ->withNotification($notification)
                ->withData(array_merge(['timestamp' => now()->toISOString()], $data));
        }

        try {
            return $this->messaging->sendAll($messages);
        } catch (\Exception $e) {
            Log::error('Firebase bulk notification failed', [
                'tokens_count' => count($deviceTokens),
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Send notification to topic
     */
    public function sendNotificationToTopic($topic, $title, $body, $data = [])
    {
        if (!$this->messaging) {
            throw new \Exception('Firebase messaging not initialized');
        }

        try {
            $notification = Notification::create($title, $body);

            $message = CloudMessage::withTarget('topic', $topic)
                ->withNotification($notification)
                ->withData(array_merge(['timestamp' => now()->toISOString()], $data));

            return $this->messaging->send($message);
        } catch (\Exception $e) {
            Log::error('Firebase topic notification failed', [
                'topic' => $topic,
                'title' => $title,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Subscribe device to topic
     */
    public function subscribeToTopic($deviceToken, $topic)
    {
        if (!$this->messaging) {
            throw new \Exception('Firebase messaging not initialized');
        }

        try {
            return $this->messaging->subscribeToTopic($topic, $deviceToken);
        } catch (\Exception $e) {
            Log::error('Firebase topic subscription failed', [
                'token' => $deviceToken,
                'topic' => $topic,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Unsubscribe device from topic
     */
    public function unsubscribeFromTopic($deviceToken, $topic)
    {
        if (!$this->messaging) {
            throw new \Exception('Firebase messaging not initialized');
        }

        try {
            return $this->messaging->unsubscribeFromTopic($topic, $deviceToken);
        } catch (\Exception $e) {
            Log::error('Firebase topic unsubscription failed', [
                'token' => $deviceToken,
                'topic' => $topic,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Validate device token
     */
    public function validateToken($deviceToken)
    {
        if (!$this->messaging) {
            return false;
        }

        try {
            // Try sending a test message to validate token
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withData(['test' => 'validation']);

            $this->messaging->validate($message);
            return true;
        } catch (\Exception $e) {
            Log::warning('Invalid Firebase token', [
                'token' => $deviceToken,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send data-only message (no notification)
     */
    public function sendDataMessage($deviceToken, $data = [])
    {
        if (!$this->messaging) {
            throw new \Exception('Firebase messaging not initialized');
        }

        try {
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withData(array_merge(['timestamp' => now()->toISOString()], $data));

            return $this->messaging->send($message);
        } catch (\Exception $e) {
            Log::error('Firebase data message failed', [
                'token' => $deviceToken,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}