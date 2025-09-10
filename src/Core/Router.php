<?php
declare(strict_types=1);

namespace CodexMundi\Core;

class Router {
    private array $routes = ['GET'=>[], 'POST'=>[]];

    public function get(string $path, $handler): void {
        $this->routes['GET'][$path] = $handler;
    }
    public function post(string $path, $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void {
        $routes = $this->routes[$method] ?? [];
        foreach ($routes as $route => $handler) {
            $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $this->invoke($handler, $matches);
                return;
            }
        }
        http_response_code(404);
        echo 'Not Found';
    }

    private function invoke($handler, array $params): void {
        if (is_array($handler) && is_string($handler[0])) {
            $class = $handler[0];
            $method = $handler[1];
            $obj = new $class();
            $obj->$method(...$params);
        } elseif (is_callable($handler)) {
            $handler(...$params);
        } else {
            throw new \RuntimeException('Invalid route handler');
        }
    }
}

