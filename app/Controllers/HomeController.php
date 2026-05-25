<?php

namespace App\Controllers;

use App\Models\Anuncio;
use Core\Controller;

class HomeController extends Controller
{
    public function health(): string
    {
        http_response_code(200);
        header('Content-Type: text/plain; charset=UTF-8');

        return 'ok';
    }

    public function index(): string
    {
        $recentAnuncios = (new Anuncio())->findRecent(6);

        return $this->view('home/index', [
            'title' => 'Trocas universitárias',
            'recentAnuncios' => $recentAnuncios,
            'categories' => config('constants.categories'),
        ]);
    }
}
