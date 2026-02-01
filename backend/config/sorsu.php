<?php

return [
    'allowed_email_domain' => env('ALLOWED_EMAIL_DOMAIN', 'sorsu.edu.ph'),
    'ai' => [
        'temperature' => 0.2,
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'daily_limit' => env('AI_DAILY_LIMIT', 10),
    ],
];
