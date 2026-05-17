<?php

namespace App\Controllers\Admin;

use App\Models\User;
use Core\Controller;
use Core\Request;

class AdminUserController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function index(): string
    {
        $request = new Request();

        return $this->view('admin/users', [
            'title' => 'Usuários',
            'users' => $this->users->paginate(['q' => trim((string) $request->query('q', ''))]),
            'q' => $request->query('q', ''),
        ], 'admin');
    }

    public function block(int $id): void
    {
        if ($id === auth_id()) {
            flash('error', 'Você não pode bloquear sua própria conta.');
            redirect('/admin/usuarios');
        }

        $this->users->updateStatus($id, 'blocked');
        flash('success', 'Usuário bloqueado.');
        redirect('/admin/usuarios');
    }

    public function unblock(int $id): void
    {
        $this->users->updateStatus($id, 'active');
        flash('success', 'Usuário desbloqueado.');
        redirect('/admin/usuarios');
    }

    public function promote(int $id): void
    {
        $this->users->updateRole($id, 'admin');
        flash('success', 'Usuário promovido a admin.');
        redirect('/admin/usuarios');
    }

    public function demote(int $id): void
    {
        if ($id === auth_id()) {
            flash('error', 'Você não pode remover seu próprio acesso admin.');
            redirect('/admin/usuarios');
        }

        $this->users->updateRole($id, 'user');
        flash('success', 'Usuário rebaixado para user.');
        redirect('/admin/usuarios');
    }
}
