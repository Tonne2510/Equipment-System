<?php

return [
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    'version' => env('RECAPTCHA_VERSION', 'v2'),
    'min_score' => env('RECAPTCHA_MIN_SCORE', 0.5), // Only for v3
];
