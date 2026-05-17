<?php

namespace App\Controllers;

use App\Models\Anuncio;
use App\Models\Avaliacao;
use App\Models\User;
use App\Services\UploadService;
use Core\Controller;

class PerfilController extends Controller
{
    public function show(int $id): string
    {
        $user = (new User())->findPublicById($id);

        if (!$user) {
            http_response_code(404);
            return $this->view('errors/404', ['title' => 'Perfil não encontrado']);
        }

        return $this->view('perfil/show', [
            'title' => $user['name'],
            'user' => $user,
            'anuncios' => (new Anuncio())->findActiveByUser($id),
            'avaliacoes' => (new Avaliacao())->findByReviewedUser($id),
        ]);
    }

    public function edit(): string
    {
        return $this->view('perfil/edit', [
            'title' => 'Editar perfil',
            'user' => auth_user(),
        ]);
    }

    public function update(): void
    {
        $errors = collect_errors([
            'name' => str_between($_POST['name'] ?? '', 3, 120) ? null : 'Nome deve ter entre 3 e 120 caracteres.',
            'institution' => trim((string) ($_POST['institution'] ?? '')) !== '' ? null : 'Informe a instituição.',
            'course' => trim((string) ($_POST['course'] ?? '')) !== '' ? null : 'Informe o curso.',
            'bio' => mb_strlen(trim((string) ($_POST['bio'] ?? ''))) <= 1000 ? null : 'Bio deve ter no maximo 1000 caracteres.',
        ]);

        if ($errors) {
            set_old($_POST);
            set_errors($errors);
            flash('error', 'Revise os dados do perfil.');
            redirect_back();
        }

        try {
            $avatarPath = (new UploadService())->store($_FILES['avatar'] ?? null, 'avatars');
        } catch (\RuntimeException $exception) {
            set_old($_POST);
            flash('error', $exception->getMessage());
            redirect_back();
        }

        (new User())->updateProfile(auth_id(), [
            'name' => trim($_POST['name']),
            'institution' => trim($_POST['institution']),
            'course' => trim($_POST['course']),
            'campus' => trim($_POST['campus'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'avatar_path' => $avatarPath,
        ]);

        flash('success', 'Perfil atualizado.');
        redirect('/perfil/' . auth_id());
    }
}
