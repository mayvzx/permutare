<?php

namespace App\Services;

class UploadService
{
    public function store(?array $file, string $folder): ?string
    {
        if (!is_upload_present($file)) {
            return null;
        }

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Falha ao receber o arquivo enviado.');
        }

        if (($file['size'] ?? 0) > config('constants.upload.max_size')) {
            throw new \RuntimeException('Imagem invalida. Envie arquivo de ate 2MB.');
        }

        $extension = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        if (!in_array($extension, config('constants.upload.allowed_extensions'), true)) {
            throw new \RuntimeException('Formato inválido. Use JPG, PNG ou WEBP.');
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!in_array($mime, config('constants.upload.allowed_mimes'), true)) {
            throw new \RuntimeException('O arquivo enviado não parece ser uma imagem válida.');
        }

        $safeFolder = trim($folder, '/');
        if (!in_array($safeFolder, ['anuncios', 'avatars'], true)) {
            throw new \RuntimeException('Destino de upload inválido.');
        }

        $directory = base_path('storage/uploads/' . $safeFolder);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        $target = $directory . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new \RuntimeException('Não foi possível salvar a imagem.');
        }

        return $safeFolder . '/' . $filename;
    }
}
