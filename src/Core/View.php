<?php
declare(strict_types=1);

namespace CodexMundi\Core;

class View {
    public static function render(string $template, array $data = []): void {
        // Compute base path so the app can run from a subdirectory (e.g. /CodexMundi/public)
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $base = rtrim($scriptDir, '/');
        if ($base === '/') { $base = ''; }

        // Lightweight header badge counts for editors/admins
        $pending_total = 0;
        if (!empty($_SESSION['user']) && in_array($_SESSION['user']['role'], ['redacteur','beheerder'], true)) {
            try {
                $db = \CodexMundi\Core\Database::conn();
                $p1 = (int)$db->query('SELECT COUNT(*) FROM photos WHERE approved=0')->fetchColumn();
                $p2 = (int)$db->query('SELECT COUNT(*) FROM wonders WHERE approved=0')->fetchColumn();
                $pending_total = $p1 + $p2;
            } catch (\Throwable $e) {
                $pending_total = 0;
            }
        }

        extract($data);
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        include __DIR__ . '/../Views/layout.php';
    }
}
