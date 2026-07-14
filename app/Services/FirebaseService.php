<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    /**
     * Get OAuth2 Access Token using JWT with Google Service Account key.
     * Caches the token for 55 minutes to optimize performance.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return Cache::remember('firebase_oauth_token', 3300, function () {
            try {
                $credentialsPath = base_path(config('services.firebase.credentials', 'storage/app/firebase/firebase-service-account.json'));

                if (!file_exists($credentialsPath)) {
                    Log::error("Firebase credentials file not found at: {$credentialsPath}");
                    return null;
                }

                $serviceAccount = json_decode(file_get_contents($credentialsPath), true);
                if (!$serviceAccount || !isset($serviceAccount['private_key'], $serviceAccount['client_email'])) {
                    Log::error("Invalid Firebase credentials JSON format.");
                    return null;
                }

                $now = time();
                $header = ['alg' => 'RS256', 'typ' => 'JWT'];
                $claim = [
                    'iss' => $serviceAccount['client_email'],
                    'sub' => $serviceAccount['client_email'],
                    'aud' => 'https://oauth2.googleapis.com/token',
                    'iat' => $now,
                    'exp' => $now + 3600,
                    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
                ];

                $payload = $this->base64UrlEncode(json_encode($header)) . '.' . $this->base64UrlEncode(json_encode($claim));
                $signature = '';

                if (!openssl_sign($payload, $signature, $serviceAccount['private_key'], 'SHA256')) {
                    Log::error("Failed to sign JWT for Firebase OAuth token.");
                    return null;
                }

                $jwt = $payload . '.' . $this->base64UrlEncode($signature);

                $response = Http::withoutVerifying()
                    ->asForm()
                    ->post('https://oauth2.googleapis.com/token', [
                        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                        'assertion' => $jwt,
                    ]);

                if ($response->failed()) {
                    Log::error("Google OAuth token request failed: " . $response->body());
                    return null;
                }

                return $response->json()['access_token'] ?? null;
            } catch (\Exception $e) {
                Log::error("Error generating Firebase OAuth token: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Send a push notification to a specific FCM device token.
     * Includes automatic retries for transient errors.
     *
     * @param string $token
     * @param string $title
     * @param string $body
     * @param array $data
     * @return bool
     */
    public function sendNotification(string $token, string $title, string $body, array $data = []): bool
    {
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            Log::error("Could not obtain Firebase Access Token. Aborting push notification.");
            return false;
        }

        $projectId = config('services.firebase.project_id') ?? $this->getProjectIdFromCredentials();
        if (!$projectId) {
            Log::error("Firebase Project ID not configured.");
            return false;
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // Construct standard FCM message payload
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data), // FCM data keys/values must be strings
                'webpush' => [
                    'fcm_options' => [
                        'link' => $data['click_action'] ?? '/',
                    ],
                    'notification' => [
                        'icon' => '/icons/icon-192x192.png',
                        'badge' => '/icons/icon-96x96.png',
                    ]
                ]
            ]
        ];

        $maxRetries = 3;
        $retryDelay = 1000; // milliseconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withoutVerifying()
                    ->withToken($accessToken)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, $payload);

                if ($response->successful()) {
                    return true;
                }

                // If token is invalid/expired, we don't retry, just return false (to handle deregistration)
                if ($response->status() === 400 || $response->status() === 404) {
                    Log::warning("FCM token invalid or not registered: {$token}. Response: " . $response->body());
                    return false;
                }

                Log::warning("FCM attempt {$attempt} failed: " . $response->body());
            } catch (\Exception $e) {
                Log::warning("FCM attempt {$attempt} encountered exception: " . $e->getMessage());
            }

            if ($attempt < $maxRetries) {
                usleep($retryDelay * 1000);
                $retryDelay *= 2; // exponential backoff
            }
        }

        return false;
    }

    /**
     * Helper to base64url encode strings for JWT tokens.
     */
    private function base64UrlEncode(string $data): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    /**
     * Fallback to parse project_id directly from the service account JSON.
     */
    private function getProjectIdFromCredentials(): ?string
    {
        try {
            $credentialsPath = base_path(config('services.firebase.credentials', 'storage/app/firebase/firebase-service-account.json'));
            if (file_exists($credentialsPath)) {
                $serviceAccount = json_decode(file_get_contents($credentialsPath), true);
                return $serviceAccount['project_id'] ?? null;
            }
        } catch (\Exception $e) {
            // ignore
        }
        return null;
    }
}
