<?php

use App\Models\User;
use Core\Session;

function auth_id(): ?int
{
    $id = Session::get('user_id');
    return $id ? (int) $id : null;
}

function auth_check(): bool
{
    return auth_id() !== null;
}

function auth_user(): ?array
{
    static $user = null;

    if (!auth_check()) {
        return null;
    }

    if ($user === null || (int) $user['id'] !== auth_id()) {
        $user = (new User())->findById(auth_id());
    }

    return $user;
}

function is_admin(): bool
{
    $user = auth_user();
    return $user && $user['role'] === 'admin';
}
