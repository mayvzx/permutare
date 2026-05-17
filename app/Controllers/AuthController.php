<?php

namespace App\Controllers;

use App\Services\AuthService;
use Core\Controller;
use Core\Request;

class AuthController extends Controller
{
    public function showLogin(): string
    {
        return $this->view('auth/login', ['title' => 'Entrar'], 'auth');
    }

    public function login(): void
    {
        $request = new Request();
        $email = trim((string) $request->post('email', ''));
        $password = (string) $request->post('password', '');

        if ($email === '' || $password === '') {
            set_old($_POST);
            flash('error', 'Informe e-mail e senha.');
            redirect_back();
        }

        try {
            $result = (new AuthService())->attempt($email, $password, $request->ip());
        } catch (\Throwable $exception) {
            error_log('[Permutare] Falha no login: ' . $exception->getMessage());
            set_old($_POST);
            flash('error', 'Não foi possível entrar agora. Verifique se o MySQL está ligado no XAMPP e tente novamente.');
            redirect_back();
        }

        if (!$result['success']) {
            set_old($_POST);
            flash('error', $result['message']);
            redirect_back();
        }

        flash('success', 'Bem-vindo ao Permutare.');
        redirect(is_admin() ? '/admin' : '/anuncios');
    }

    public function showRegister(): string
    {
        return $this->view('auth/register', ['title' => 'Criar conta'], 'auth');
    }

    public function register(): void
    {
        try {
            $result = (new AuthService())->register($_POST);
        } catch (\Throwable $exception) {
            error_log('[Permutare] Falha no cadastro: ' . $exception->getMessage());
            set_old($_POST);
            flash('error', 'Não foi possível criar a conta agora. Verifique se o MySQL está ligado no XAMPP e tente novamente.');
            redirect_back();
        }

        if (!$result['success']) {
            set_old($_POST);
            set_errors($result['errors']);
            flash('error', 'Revise os campos destacados.');
            redirect_back();
        }

        flash('success', 'Conta criada. Em ambiente local, use o link de verificação exibido abaixo.');
        redirect('/verificar-email?novo=1&link=' . urlencode($result['verification_link']));
    }

    public function verifyEmail(): string
    {
        $request = new Request();
        $token = (string) $request->query('token', '');

        if ($token !== '') {
            $verified = (new AuthService())->verifyEmail($token);
            flash($verified ? 'success' : 'error', $verified ? 'E-mail verificado com sucesso.' : 'Token inválido ou expirado.');
            redirect('/login');
        }

        return $this->view('auth/verify-email', [
            'title' => 'Verificar e-mail',
            'verificationLink' => $request->query('link'),
        ], 'auth');
    }

    public function logout(): void
    {
        (new AuthService())->logout();
        flash('success', 'Você saiu da sua conta.');
        redirect('/');
    }
}
