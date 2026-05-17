<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserScore;
use Core\Database;
use Core\Session;
use PDO;

class AuthService
{
    private PDO $db;
    private User $users;

    public function __construct()
    {
        $this->db = Database::connection();
        $this->users = new User();
    }

    public function register(array $data): array
    {
        $errors = $this->validateRegister($data);

        if ($errors) {
            return ['success' => false, 'errors' => $errors];
        }

        $this->db->beginTransaction();

        try {
            $userId = $this->users->create([
                'name' => trim($data['name']),
                'email' => trim($data['email']),
                'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            $this->users->createProfile($userId, [
                'institution' => trim($data['institution']),
                'course' => trim($data['course']),
                'campus' => trim($data['campus'] ?? ''),
            ]);

            (new UserScore())->createInitial($userId);

            $token = bin2hex(random_bytes(32));
            $stmt = $this->db->prepare(
                "INSERT INTO email_verifications (user_id, token, expires_at, created_at)
                 VALUES (:user_id, :token, DATE_ADD(NOW(), INTERVAL 24 HOUR), NOW())"
            );
            $stmt->execute([
                'user_id' => $userId,
                'token' => hash('sha256', $token),
            ]);

            $this->db->commit();

            return [
                'success' => true,
                'user_id' => $userId,
                'verification_link' => url('/verificar-email?token=' . $token),
            ];
        } catch (\Throwable $exception) {
            $this->safeRollback();
            error_log('[Permutare] Falha ao cadastrar usuário: ' . $exception->getMessage());

            return [
                'success' => false,
                'errors' => [
                    'database' => 'Não foi possível criar a conta agora. Verifique se o MySQL está ligado no XAMPP e tente novamente.',
                ],
            ];
        }
    }

    private function safeRollback(): void
    {
        try {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
        } catch (\Throwable $rollbackException) {
            error_log('[Permutare] Falha ao desfazer transação: ' . $rollbackException->getMessage());
        }
    }

    public function attempt(string $email, string $password, string $ip): array
    {
        if ($this->isRateLimited($email, $ip)) {
            return ['success' => false, 'message' => 'Muitas tentativas recentes. Aguarde alguns minutos e tente novamente.'];
        }

        $user = $this->users->findByEmail($email);
        $valid = $user && password_verify($password, $user['password_hash']);

        $this->recordAttempt($email, $ip, $valid);

        if (!$valid) {
            return ['success' => false, 'message' => 'Credenciais inválidas.'];
        }

        if ($user['status'] === 'blocked') {
            return ['success' => false, 'message' => 'Sua conta está bloqueada. Procure a administração da plataforma.'];
        }

        if ($user['status'] === 'deleted') {
            return ['success' => false, 'message' => 'Credenciais inválidas.'];
        }

        Session::regenerate();
        Session::set('user_id', (int) $user['id']);

        return ['success' => true, 'user' => $user];
    }

    public function logout(): void
    {
        Session::destroy();
        Session::start();
        Session::regenerate();
    }

    public function verifyEmail(string $plainToken): bool
    {
        $hash = hash('sha256', $plainToken);
        $stmt = $this->db->prepare(
            "SELECT * FROM email_verifications
             WHERE token = :token AND used_at IS NULL AND expires_at >= NOW()
             LIMIT 1"
        );
        $stmt->execute(['token' => $hash]);
        $verification = $stmt->fetch();

        if (!$verification) {
            return false;
        }

        $this->db->beginTransaction();
        try {
            $this->db->prepare("UPDATE users SET email_verified_at = NOW(), updated_at = NOW() WHERE id = :id")
                ->execute(['id' => $verification['user_id']]);
            $this->db->prepare("UPDATE email_verifications SET used_at = NOW() WHERE id = :id")
                ->execute(['id' => $verification['id']]);
            $this->db->commit();

            return true;
        } catch (\Throwable $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    private function validateRegister(array $data): array
    {
        $errors = collect_errors([
            'name' => str_between($data['name'] ?? '', 3, 120) ? null : 'Informe um nome entre 3 e 120 caracteres.',
            'email' => filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL) ? null : 'Informe um e-mail válido.',
            'password' => strlen((string) ($data['password'] ?? '')) >= 8 ? null : 'A senha deve ter pelo menos 8 caracteres.',
            'password_confirmation' => (($data['password'] ?? '') === ($data['password_confirmation'] ?? '')) ? null : 'A confirmação de senha não confere.',
            'institution' => trim((string) ($data['institution'] ?? '')) !== '' ? null : 'Informe a instituição.',
            'course' => trim((string) ($data['course'] ?? '')) !== '' ? null : 'Informe o curso.',
            'terms' => !empty($data['terms']) ? null : 'Você precisa aceitar os termos simples da plataforma.',
        ]);

        if (empty($errors['email']) && $this->users->findByEmail($data['email'])) {
            $errors['email'] = 'Este e-mail já está cadastrado.';
        }

        return $errors;
    }

    private function isRateLimited(string $email, string $ip): bool
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM login_attempts
             WHERE success = 0
               AND attempted_at >= DATE_SUB(NOW(), INTERVAL 15 MINUTE)
               AND (email = :email OR ip_address = :ip)"
        );
        $stmt->execute([
            'email' => mb_strtolower(trim($email)),
            'ip' => $ip,
        ]);

        return (int) $stmt->fetchColumn() >= 5;
    }

    private function recordAttempt(string $email, string $ip, bool $success): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO login_attempts (email, ip_address, attempted_at, success)
             VALUES (:email, :ip_address, NOW(), :success)"
        );
        $stmt->execute([
            'email' => mb_strtolower(trim($email)),
            'ip_address' => $ip,
            'success' => $success ? 1 : 0,
        ]);
    }
}
