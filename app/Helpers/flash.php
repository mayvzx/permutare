<?php

use Core\Session;

function flash(string $type, string $message): void
{
    Session::set('flash', ['type' => $type, 'message' => $message]);
}

function get_flash(): ?array
{
    $flash = Session::get('flash');
    Session::forget('flash');
    return $flash;
}

function set_old(array $data): void
{
    unset($data['password'], $data['password_confirmation'], $data['_csrf']);
    Session::set('_old', $data);
}

function set_errors(array $errors): void
{
    Session::set('_errors', $errors);
}

function get_errors(): array
{
    $errors = Session::get('_errors', []);
    Session::forget('_errors');
    return $errors;
}

function old(string $key, mixed $default = ''): mixed
{
    $old = Session::get('_old', []);
    return $old[$key] ?? $default;
}

function clear_old(): void
{
    Session::forget('_old');
}
