<?php
declare(strict_types=1);

namespace CodexMundi\Controllers;

use CodexMundi\Core\View;

class BaseController {
    protected function view(string $template, array $data = []): void {
        $data['template'] = $template;
        $data['user'] = $_SESSION['user'] ?? null;
        View::render($template, $data);
    }

    protected function redirect(string $path): void {
        // Ensure redirects work from a subdirectory by prefixing base path for absolute URLs
        if (isset($path[0]) && $path[0] === '/') {
            $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
            $base = rtrim($scriptDir, '/');
            if ($base === '/') { $base = ''; }
            $path = $base . $path;
        }
        header('Location: ' . $path);
        exit;
    }

    protected function requireRole(array $roles): void {
        $user = $_SESSION['user'] ?? null;
        if (!$user || !in_array($user['role'], $roles, true)) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Onvoldoende rechten.'];
            $this->redirect('/');
        }
    }
}
