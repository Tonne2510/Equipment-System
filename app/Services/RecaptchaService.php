<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    private string $secretKey;
    private string $siteKey;
    private string $version;
    private string $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct()
    {
        $this->secretKey = config('recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY');
        $this->siteKey = config('recaptcha.site_key') ?? env('RECAPTCHA_SITE_KEY');
        $this->version = config('recaptcha.version') ?? env('RECAPTCHA_VERSION', 'v2');
    }

    /**
     * Verify reCAPTCHA token/response
     * For v2: Validates the response token from checkbox
     * For v3: Validates the response token and checks score
     *
     * @param string $token - The token from client-side (g-recaptcha-response for v2, g-recaptcha-token for v3)
     * @param float $minScore - Minimum score required (0.0-1.0), only used for v3
     * @return bool
     */
    public function verify(string $token, float $minScore = 0.5): bool
    {
        if (empty($this->secretKey)) {
            \Log::error('reCAPTCHA secret key is not configured');
            return false;
        }

        if (empty($token)) {
            \Log::error('reCAPTCHA token is empty');
            return false;
        }

        // Sanitize token - remove whitespace
        $token = trim($token);

        if (empty($token)) {
            \Log::error('reCAPTCHA token is empty after sanitization');
            return false;
        }

        try {
            \Log::debug('reCAPTCHA verification starting', [
                'version' => $this->version,
                'secret_key_set' => !empty($this->secretKey),
                'site_key_set' => !empty($this->siteKey),
                'token_length' => strlen($token),
                'app_env' => env('APP_ENV'),
            ]);

            $response = Http::timeout(10)
                ->withoutVerifying()  // Disable SSL verification for local development
                ->asForm()
                ->post($this->verifyUrl, [
                    'secret' => $this->secretKey,
                    'response' => $token,
                ]);

            $result = $response->json();

            \Log::debug('reCAPTCHA Response:', [
                'status_code' => $response->status(),
                'version' => $this->version,
                'success' => $result['success'] ?? 'N/A',
                'score' => $result['score'] ?? 'N/A',
                'action' => $result['action'] ?? 'N/A',
                'challenge_ts' => $result['challenge_ts'] ?? 'N/A',
                'hostname' => $result['hostname'] ?? 'N/A',
                'error_codes' => $result['error-codes'] ?? [],
            ]);

            // Check if response was successful
            if (!isset($result['success'])) {
                \Log::error('reCAPTCHA missing success field', $result);
                return false;
            }

            if (!$result['success']) {
                \Log::error('reCAPTCHA verification failed', [
                    'success' => $result['success'],
                    'error_codes' => $result['error-codes'] ?? [],
                    'version' => $this->version,
                ]);
                return false;
            }

            // For v3, check score
            if ($this->version === 'v3' && isset($result['score'])) {
                \Log::debug('reCAPTCHA v3 Score: ' . $result['score'] . ' (minimum: ' . $minScore . ')');
                if ($result['score'] < $minScore) {
                    \Log::warning('reCAPTCHA v3 score too low', [
                        'score' => $result['score'],
                        'min' => $minScore,
                        'action' => $result['action'] ?? 'N/A',
                    ]);
                    return false;
                }
            }

            \Log::debug('reCAPTCHA verification successful', [
                'version' => $this->version,
                'score' => $result['score'] ?? 'N/A',
            ]);
            return true;
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA verification error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'version' => $this->version,
            ]);
            return false;
        }
    }

    /**
     * Get site key for frontend
     */
    public function getSiteKey(): string
    {
        return $this->siteKey ?: '';
    }

    /**
     * Get version
     */
    public function getVersion(): string
    {
        return $this->version;
    }
}
