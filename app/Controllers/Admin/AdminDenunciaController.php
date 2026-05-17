<?php

namespace App\Controllers\Admin;

use App\Models\Denuncia;
use App\Models\User;
use Core\Controller;
use Core\Request;

class AdminDenunciaController extends Controller
{
    private Denuncia $denuncias;

    public function __construct()
    {
        $this->denuncias = new Denuncia();
    }

    public function index(): string
    {
        $request = new Request();

        return $this->view('admin/denuncias', [
            'title' => 'Denúncias',
            'denuncias' => $this->denuncias->paginate(['status' => $request->query('status', '')]),
            'status' => $request->query('status', ''),
        ], 'admin');
    }

    public function review(int $id): void
    {
        $this->denuncias->updateStatus($id, 'reviewing', $_POST['admin_notes'] ?? null);
        flash('success', 'Denúncia marcada em análise.');
        redirect('/admin/denuncias');
    }

    public function resolve(int $id): void
    {
        $this->denuncias->updateStatus($id, 'resolved', $_POST['admin_notes'] ?? null);

        if (!empty($_POST['block_user_id'])) {
            (new User())->updateStatus((int) $_POST['block_user_id'], 'blocked');
        }

        flash('success', 'Denúncia resolvida.');
        redirect('/admin/denuncias');
    }

    public function archive(int $id): void
    {
        $this->denuncias->updateStatus($id, 'archived', $_POST['admin_notes'] ?? null);
        flash('success', 'Denúncia arquivada.');
        redirect('/admin/denuncias');
    }
}
