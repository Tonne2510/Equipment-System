<?php
// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

echo "=== reCAPTCHA Configuration Debug ===\n\n";

echo "From .env file:\n";
echo "RECAPTCHA_SITE_KEY=" . (env('RECAPTCHA_SITE_KEY') ?: 'NOT SET') . "\n";
echo "RECAPTCHA_SECRET_KEY=" . (env('RECAPTCHA_SECRET_KEY') ?: 'NOT SET') . "\n";

echo "\n\nFrom config:\n";
echo "config('recaptcha.site_key')=" . (config('recaptcha.site_key') ?: 'NOT SET') . "\n";
echo "config('recaptcha.secret_key')=" . (config('recaptcha.secret_key') ?: 'NOT SET') . "\n";

echo "\n\nKeys match: " . (env('RECAPTCHA_SITE_KEY') === config('recaptcha.site_key') ? 'YES' : 'NO') . "\n";

echo "\n\nSite Key Length: " . strlen(config('recaptcha.site_key')) . "\n";
echo "Secret Key Length: " . strlen(config('recaptcha.secret_key')) . "\n";

echo "\n\nTest Key Signatures:\n";
$testSiteKey = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
$testSecretKey = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

echo "Expected Site Key: " . $testSiteKey . "\n";
echo "Expected Secret Key: " . $testSecretKey . "\n";

echo "\nSite Key matches: " . (config('recaptcha.site_key') === $testSiteKey ? 'YES ✓' : 'NO ✗') . "\n";
echo "Secret Key matches: " . (config('recaptcha.secret_key') === $testSecretKey ? 'YES ✓' : 'NO ✗') . "\n";
