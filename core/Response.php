<?php

namespace Core;

class Response
{
    public static function notFound(): void
    {
        http_response_code(404);
        echo view('errors/404', [], 'app');
    }

    public static function forbidden(): void
    {
        http_response_code(403);
        echo 'Acesso negado.';
    }
}
