<?php

return [
    'name' => env('APP_NAME', 'Permutare'),
    'env' => env('APP_ENV', 'local'),
    'debug' => filter_var(env('APP_DEBUG', true), FILTER_VALIDATE_BOOLEAN),
    'url' => rtrim(env('APP_URL', 'http://localhost/permutare/public'), '/'),
    'session_name' => env('SESSION_NAME', 'permutare_session'),
];
