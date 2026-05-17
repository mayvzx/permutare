<?php

namespace App\Controllers\Admin;

use App\Models\Anuncio;
use App\Models\Denuncia;
use App\Models\Proposta;
use App\Models\User;
use Core\Controller;

class AdminDashboardController extends Controller
{
    public function index(): string
    {
        $users = new User();
        $anuncios = new Anuncio();
        $denuncias = new Denuncia();

        return $this->view('admin/dashboard', [
            'title' => 'Admin',
            'stats' => [
                'users' => $users->countAll(),
                'active_anuncios' => $anuncios->countByStatus('active'),
                'propostas' => (new Proposta())->countAll(),
                'pending_denuncias' => $denuncias->countPending(),
            ],
            'latestUsers' => $users->latest(),
            'latestAnuncios' => $anuncios->latest(),
            'latestDenuncias' => $denuncias->latest(),
        ], 'admin');
    }
}
