<?php

function load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }
}

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? getenv($key);
    return $value === false ? $default : $value;
}

function config(string $key, mixed $default = null): mixed
{
    $segments = explode('.', $key);
    $value = $GLOBALS['config'] ?? [];

    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }

        $value = $value[$segment];
    }

    return $value;
}

function base_path(string $path = ''): string
{
    return BASE_PATH . ($path ? '/' . ltrim($path, '/') : '');
}

function url(string $path = ''): string
{
    $base = rtrim(config('app.url'), '/');
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function versioned_asset(string $path): string
{
    $relativePath = ltrim($path, '/');
    $file = base_path('public/assets/' . $relativePath);
    $version = is_file($file) ? filemtime($file) : time();

    return asset($relativePath) . '?v=' . $version;
}

function upload_url(?string $path): string
{
    if (!$path) {
        return versioned_asset('img/generated/permutare-placeholder-item.webp');
    }

    return url('uploads/' . ltrim($path, '/'));
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function redirect_back(): never
{
    $fallback = url('/');
    $previous = $_SERVER['HTTP_REFERER'] ?? $fallback;
    header('Location: ' . $previous);
    exit;
}
