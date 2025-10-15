<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class FcmDirectService
{
    private string $projectId;
    private string $credentialsPath;
    private array $serviceAccount;

    public function __construct()
    {
        $this->credentialsPath = storage_path('app/firebase/firebase-credentials.json');
        
        if (!file_exists($this->credentialsPath)) {
            throw new \Exception('Firebase credentials file not found at: ' . $this->credentialsPath);
        }
        
        $this->serviceAccount = json_decode(file_get_contents($this->credentialsPath), true);
        $this->projectId = $this->serviceAccount['project_id']; 
        
        Log::info('FCM Direct Service initialized', [
            'project_id' => $this->projectId,
            'credentials_exists' => true,
            'service_account_email' => $this->serviceAccount['client_email'] ?? 'unknown'
        ]);
    }

    /**
     * Generate JWT token manually untuk OAuth 2.0
     */
    private function generateJWT(): string
    {
        $now = time();
        $payload = [
            'iss' => $this->serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600, // 1 hour
        ];

        return JWT::encode($payload, $this->serviceAccount['private_key'], 'RS256');
    }

    /**
     * Get OAuth 2.0 access token
     */
    private function getAccessToken(): string
    {
        try {
            $jwt = $this->generateJWT();
            
            $response = Http::asForm()
                ->withOptions([
                    'verify' => false, // Bypass SSL verification for development
                ])
                ->post('https://oauth2.googleapis.com/token', [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get access token: ' . $response->body());
            }

            $data = $response->json();
            
            if (!isset($data['access_token'])) {
                throw new \Exception('Access token not found in response');
            }

            Log::info('Access token obtained successfully');
            return $data['access_token'];
            
        } catch (\Exception $e) {
            Log::error('Failed to get access token', [
                'error' => $e->getMessage(),
                'credentials_path' => $this->credentialsPath
            ]);
            throw $e;
        }
    }

    /**
     * Send notification to multiple device tokens
     */
    public function sendNotification(array $deviceTokens, string $title, string $body, string $route, ?string $img = null): bool
    {
        try {
            $token = $this->getAccessToken();
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ];

            $payloadData = [
                'title' => $title,
                'body' => $body,
                'status' => 'Done',
                'route' => $route,
            ];

            if ($img) {
                $payloadData['imgUrl'] = $img;
            }

            Log::info('Sending FCM notifications with Direct Service', [
                'device_count' => count($deviceTokens),
                'title' => $title,
                'body' => $body,
                'route' => $route,
                'has_image' => !is_null($img)
            ]);

            $successCount = 0;
            $failCount = 0;

            foreach ($deviceTokens as $index => $deviceToken) {
                try {
                    $response = Http::withHeaders($headers)
                        ->acceptJson()
                        ->timeout(30)
                        ->withOptions([
                            'verify' => false, 
                        ])
                        ->post($url, [
                            'message' => [
                                'token' => $deviceToken,
                                'data' => $payloadData,
                                'notification' => [
                                    'title' => $title,
                                    'body' => $body,
                                    'image' => $img
                                ],
                                'android' => [
                                    'priority' => 'high',
                                    'notification' => [
                                        'title' => $title,
                                        'body' => $body,
                                        'image' => $img,
                                        'channel_id' => 'default',
                                        'sound' => 'default'
                                    ],
                                    'data' => $payloadData
                                ],
                                'apns' => [
                                    'headers' => [
                                        'apns-priority' => '10'
                                    ],
                                    'payload' => [
                                        'aps' => [
                                            'alert' => [
                                                'title' => $title,
                                                'body' => $body
                                            ],
                                            'sound' => 'default',
                                            'badge' => 1
                                        ]
                                    ]
                                ]
                            ],
                        ]);

                    if ($response->successful()) {
                        $successCount++;
                        Log::info('FCM notification sent successfully', [
                            'token_index' => $index + 1,
                            'token_preview' => substr($deviceToken, 0, 20) . '...',
                            'response' => $response->json()
                        ]);
                    } else {
                        $failCount++;
                        Log::warning('FCM notification failed', [
                            'token_index' => $index + 1,
                            'token_preview' => substr($deviceToken, 0, 20) . '...',
                            'status' => $response->status(),
                            'response' => $response->body()
                        ]);
                    }

                } catch (\Exception $e) {
                    $failCount++;
                    Log::error('FCM notification exception', [
                        'token_index' => $index + 1,
                        'token_preview' => substr($deviceToken, 0, 20) . '...',
                        'error' => $e->getMessage()
                    ]);
                }

                if (count($deviceTokens) > 1) {
                    usleep(100000);
                }
            }

            Log::info('FCM notification batch completed', [
                'total_tokens' => count($deviceTokens),
                'success_count' => $successCount,
                'fail_count' => $failCount,
                'success_rate' => round(($successCount / count($deviceTokens)) * 100, 2) . '%'
            ]);

            return $successCount > 0;

        } catch (\Throwable $e) {
            Log::error('FCM Direct Service Error: ' . $e->getMessage(), [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }

    /**
     * Send notification to single device token
     */
    public function sendToToken(string $deviceToken, string $title, string $body, string $route, ?string $img = null): bool
    {
        return $this->sendNotification([$deviceToken], $title, $body, $route, $img);
    }

    /**
     * Send test notification
     */
    public function sendTestNotification(string $deviceToken): bool
    {
        return $this->sendToToken(
            $deviceToken,
            '🔥 Direct FCM Test',
            'This is a test notification using Direct FCM Service at ' . now()->format('H:i:s'),
            '/test',
            null
        );
    }
}