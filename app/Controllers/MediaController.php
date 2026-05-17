<?php

namespace App\Controllers;

use Core\Controller;

class MediaController extends Controller
{
    public function show(string $folder, string $filename): void
    {
        if (!in_array($folder, ['anuncios', 'avatars'], true) || !preg_match('/^[a-f0-9]{32}\.(jpg|jpeg|png|webp)$/i', $filename)) {
            http_response_code(404);
            exit;
        }

        $path = base_path('storage/uploads/' . $folder . '/' . $filename);

        if (!is_file($path)) {
            http_response_code(404);
            exit;
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($path);

        if (!in_array($mime, config('constants.upload.allowed_mimes'), true)) {
            http_response_code(404);
            exit;
        }

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}
