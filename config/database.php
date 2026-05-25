<?php

return [
    'driver' => env('DB_CONNECTION', env('DB_DRIVER', 'mysql')),
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', '3306'),
    'name' => env('DB_NAME', 'permutare'),
    'user' => env('DB_USER', 'root'),
    'pass' => env('DB_PASS', ''),
    'charset' => env('DB_CHARSET', 'utf8mb4'),
    'sslmode' => env('DB_SSLMODE', 'prefer'),
];
