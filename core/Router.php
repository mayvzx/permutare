<?php

namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, array $handler, array $middlewares = []): void
    {
        $this->add('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, array $handler, array $middlewares = []): void
    {
        $this->add('POST', $path, $handler, $middlewares);
    }

    private function add(string $method, string $path, array $handler, array $middlewares): void
    {
        $this->routes[] = compact('method', 'path', 'handler', 'middlewares');
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();

        foreach ($this->routes as $route) {
            $params = $this->match($route['path'], $path);

            if ($route['method'] === $method && $params !== false) {
                $this->runMiddlewares($route['middlewares'], $method);

                [$controllerClass, $action] = $route['handler'];
                $controller = new $controllerClass();
                $response = $controller->{$action}(...array_values($params));

                if (is_string($response)) {
                    echo $response;
                }

                return;
            }
        }

        http_response_code(404);
        echo view('errors/404', ['path' => $path], 'app');
    }

    private function match(string $routePath, string $requestPath): array|false
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (!preg_match($pattern, $requestPath, $matches)) {
            return false;
        }

        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    private function runMiddlewares(array $middlewares, string $method): void
    {
        if ($method === 'POST' && !in_array('skip_csrf', $middlewares, true) && !csrf_verify($_POST['_csrf'] ?? null)) {
            flash('error', 'Sessão expirada. Tente novamente.');
            redirect_back();
        }

        if (in_array('guest', $middlewares, true) && auth_check()) {
            redirect('/anuncios');
        }

        if (in_array('auth', $middlewares, true) && !auth_check()) {
            flash('warning', 'Entre para continuar.');
            redirect('/login');
        }

        if (in_array('admin', $middlewares, true)) {
            if (!auth_check()) {
                flash('warning', 'Entre para continuar.');
                redirect('/login');
            }

            if (!is_admin()) {
                flash('error', 'Você não tem permissão para acessar esta área.');
                redirect('/');
            }
        }
    }
}
