<?php

use Core\Session;

function csrf_token(): string
{
    $token = Session::get('_csrf_token');

    if (!$token) {
        $token = bin2hex(random_bytes(32));
        Session::set('_csrf_token', $token);
    }

    return $token;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    return is_string($token) && hash_equals(Session::get('_csrf_token', ''), $token);
}
