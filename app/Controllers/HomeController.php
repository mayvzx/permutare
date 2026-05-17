<?php

namespace App\Controllers;

use App\Models\Anuncio;
use Core\Controller;

class HomeController extends Controller
{
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
