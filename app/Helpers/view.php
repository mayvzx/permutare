<?php

function e(mixed $value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function view(string $view, array $data = [], string $layout = 'app'): string
{
    $viewFile = BASE_PATH . '/app/Views/' . $view . '.php';

    if (!is_file($viewFile)) {
        throw new RuntimeException("View não encontrada: {$view}");
    }

    extract($data, EXTR_SKIP);

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    if ($layout === null || $layout === '') {
        clear_old();
        return $content;
    }

    $layoutFile = BASE_PATH . '/app/Views/layouts/' . $layout . '.php';

    if (!is_file($layoutFile)) {
        throw new RuntimeException("Layout não encontrado: {$layout}");
    }

    ob_start();
    require $layoutFile;
    $rendered = ob_get_clean();
    clear_old();

    return $rendered;
}

function partial(string $partial, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require BASE_PATH . '/app/Views/partials/' . $partial . '.php';
}
