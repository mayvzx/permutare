<?php

namespace App\Controllers;

use App\Models\Anuncio;
use App\Services\UploadService;
use Core\Controller;
use Core\Request;

class AnuncioController extends Controller
{
    private Anuncio $anuncios;

    public function __construct()
    {
        $this->anuncios = new Anuncio();
    }

    public function index(): string
    {
        $request = new Request();
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'category' => $request->query('category'),
            'condition' => $request->query('condition'),
            'sort' => $request->query('sort', 'recent'),
        ];
        $page = max(1, (int) $request->query('page', 1));

        return $this->view('anuncios/index', [
            'title' => 'Explorar anúncios',
            'anuncios' => $this->anuncios->paginateActive($filters, $page),
            'filters' => $filters,
            'page' => $page,
        ]);
    }

    public function show(int $id): string
    {
        $anuncio = $this->anuncios->findPublicById($id);

        if (!$anuncio) {
            http_response_code(404);
            return $this->view('errors/404', ['title' => 'Anúncio não encontrado']);
        }

        if ($anuncio['status'] === 'active') {
            $this->anuncios->incrementViews($id);
        }

        return $this->view('anuncios/show', [
            'title' => $anuncio['title'],
            'anuncio' => $anuncio,
        ]);
    }

    public function create(): string
    {
        return $this->view('anuncios/create', ['title' => 'Criar anúncio']);
    }

    public function store(): void
    {
        $errors = $this->validate($_POST);

        if ($errors) {
            set_old($_POST);
            set_errors($errors);
            flash('error', 'Revise os dados do anúncio.');
            redirect_back();
        }

        try {
            $imagePath = (new UploadService())->store($_FILES['image'] ?? null, 'anuncios');
        } catch (\RuntimeException $exception) {
            set_old($_POST);
            flash('error', $exception->getMessage());
            redirect_back();
        }

        $id = $this->anuncios->create([
            'user_id' => auth_id(),
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'category' => $_POST['category'],
            'item_condition' => $_POST['item_condition'],
            'desired_item' => trim($_POST['desired_item']),
            'image_path' => $imagePath,
        ]);

        flash('success', 'Anúncio criado com sucesso.');
        redirect('/anuncios/' . $id);
    }

    public function edit(int $id): string
    {
        $anuncio = $this->guardOwner($id);

        if (in_array($anuncio['status'], ['completed', 'removed'], true)) {
            flash('error', 'Este anúncio não pode mais ser editado.');
            redirect('/meus-anuncios');
        }

        return $this->view('anuncios/edit', ['title' => 'Editar anúncio', 'anuncio' => $anuncio]);
    }

    public function update(int $id): void
    {
        $anuncio = $this->guardOwner($id);

        if (in_array($anuncio['status'], ['completed', 'removed'], true)) {
            flash('error', 'Este anúncio não pode mais ser editado.');
            redirect('/meus-anuncios');
        }

        $errors = $this->validate($_POST);

        if ($errors) {
            set_old($_POST);
            set_errors($errors);
            flash('error', 'Revise os dados do anúncio.');
            redirect_back();
        }

        try {
            $imagePath = (new UploadService())->store($_FILES['image'] ?? null, 'anuncios');
        } catch (\RuntimeException $exception) {
            set_old($_POST);
            flash('error', $exception->getMessage());
            redirect_back();
        }

        $this->anuncios->update($id, [
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description']),
            'category' => $_POST['category'],
            'item_condition' => $_POST['item_condition'],
            'desired_item' => trim($_POST['desired_item']),
            'image_path' => $imagePath,
        ]);

        flash('success', 'Anúncio atualizado.');
        redirect('/anuncios/' . $id);
    }

    public function pause(int $id): void
    {
        $anuncio = $this->guardOwner($id);
        $newStatus = $anuncio['status'] === 'paused' ? 'active' : 'paused';
        $this->anuncios->updateStatus($id, $newStatus);

        flash('success', $newStatus === 'paused' ? 'Anúncio pausado.' : 'Anúncio reativado.');
        redirect('/meus-anuncios');
    }

    public function remove(int $id): void
    {
        $this->guardOwner($id);
        $this->anuncios->updateStatus($id, 'removed');

        flash('success', 'Anúncio removido.');
        redirect('/meus-anuncios');
    }

    public function my(): string
    {
        return $this->view('anuncios/my', [
            'title' => 'Meus anúncios',
            'anuncios' => $this->anuncios->findByUser(auth_id()),
        ]);
    }

    private function validate(array $data): array
    {
        return collect_errors([
            'title' => str_between($data['title'] ?? '', 5, 120) ? null : 'Titulo deve ter entre 5 e 120 caracteres.',
            'description' => str_between($data['description'] ?? '', 20, 2000) ? null : 'Descricao deve ter entre 20 e 2000 caracteres.',
            'category' => valid_choice($data['category'] ?? null, config('constants.categories')) ? null : 'Categoria invalida.',
            'item_condition' => valid_choice($data['item_condition'] ?? null, config('constants.conditions')) ? null : 'Condicao invalida.',
            'desired_item' => str_between($data['desired_item'] ?? '', 3, 255) ? null : 'Informe o que você deseja receber.',
        ]);
    }

    private function guardOwner(int $id): array
    {
        $anuncio = $this->anuncios->findById($id);

        if (!$anuncio || (int) $anuncio['user_id'] !== auth_id()) {
            flash('error', 'Você não tem permissão para acessar este anúncio.');
            redirect('/meus-anuncios');
        }

        return $anuncio;
    }
}
