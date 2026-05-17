<?php

namespace Core;

abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'app'): string
    {
        return view($view, $data, $layout);
    }

    protected function redirect(string $path): never
    {
        redirect($path);
    }

    protected function back(): never
    {
        redirect_back();
    }

    protected function requireAuth(): void
    {
        if (!auth_check()) {
            flash('warning', 'Entre para continuar.');
            redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (!is_admin()) {
            flash('error', 'Você não tem permissão para acessar esta área.');
            redirect('/');
        }
    }
}
