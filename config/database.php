<?php

$databaseUrl = env('DATABASE_URL', '');

if ($databaseUrl !== '') {
    $parts = parse_url($databaseUrl);

    if ($parts !== false && isset($parts['scheme'])) {
        $scheme = strtolower($parts['scheme']);
        $driver = in_array($scheme, ['postgres', 'postgresql', 'pgsql'], true) ? 'pgsql' : $scheme;
        $query = [];

        if (isset($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        return [
            'driver' => $driver,
            'host' => $parts['host'] ?? 'localhost',
            'port' => (string) ($parts['port'] ?? ($driver === 'pgsql' ? 5432 : 3306)),
            'name' => isset($parts['path']) ? ltrim(rawurldecode($parts['path']), '/') : '',
            'user' => isset($parts['user']) ? rawurldecode($parts['user']) : '',
            'pass' => isset($parts['pass']) ? rawurldecode($parts['pass']) : '',
            'charset' => env('DB_CHARSET', $driver === 'pgsql' ? 'utf8' : 'utf8mb4'),
            'sslmode' => $query['sslmode'] ?? env('DB_SSLMODE', $driver === 'pgsql' ? 'require' : 'prefer'),
        ];
    }
}

$driver = env('DB_CONNECTION', env('DB_DRIVER', 'mysql'));

return [
    'driver' => $driver,
    'host' => env('DB_HOST', 'localhost'),
    'port' => env('DB_PORT', $driver === 'pgsql' ? '5432' : '3306'),
    'name' => env('DB_NAME', 'permutare'),
    'user' => env('DB_USER', 'root'),
    'pass' => env('DB_PASS', ''),
    'charset' => env('DB_CHARSET', $driver === 'pgsql' ? 'utf8' : 'utf8mb4'),
    'sslmode' => env('DB_SSLMODE', $driver === 'pgsql' ? 'require' : 'prefer'),
];
