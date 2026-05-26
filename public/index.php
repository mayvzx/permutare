<?php

use Core\Request;

try {
    $router = require dirname(__DIR__) . '/bootstrap/app.php';
    $router->dispatch(new Request());
} catch (Throwable $exception) {
    error_log(sprintf(
        '[Permutare] Unhandled exception: %s in %s:%d',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    ));

    if (function_exists('config') && config('app.debug')) {
        throw $exception;
    }

    http_response_code(500);
    echo 'Erro interno da aplicação.';
}
