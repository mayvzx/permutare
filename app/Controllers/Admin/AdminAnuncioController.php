<?php

namespace App\Controllers\Admin;

use App\Models\Anuncio;
use Core\Controller;
use Core\Request;

class AdminAnuncioController extends Controller
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
            'status' => $request->query('status', ''),
        ];

        return $this->view('admin/anuncios', [
            'title' => 'Moderação de anúncios',
            'anuncios' => $this->anuncios->adminList($filters),
            'filters' => $filters,
        ], 'admin');
    }

    public function remove(int $id): void
    {
        $this->anuncios->updateStatus($id, 'removed');
        flash('success', 'Anúncio removido pela moderação.');
        redirect('/admin/anuncios');
    }
}
