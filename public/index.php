<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../src/bootstrap.php';

use CodexMundi\Core\Router;
use CodexMundi\Controllers\AuthController;
use CodexMundi\Controllers\WonderController;
use CodexMundi\Controllers\MediaController;
use CodexMundi\Controllers\AdminController;
use CodexMundi\Controllers\WonderController as WC;

$router = new Router();

// Home / Wonders listing (public)
$router->get('/', [WonderController::class, 'index']);

// Auth
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

// Wonders CRUD
$router->get('/wonders/create', [WonderController::class, 'create']);
$router->post('/wonders', [WonderController::class, 'store']);
$router->get('/wonders/{id}', [WonderController::class, 'show']);
$router->get('/wonders/{id}/edit', [WonderController::class, 'edit']);
$router->post('/wonders/{id}', [WonderController::class, 'update']);
$router->post('/wonders/{id}/delete', [WonderController::class, 'destroy']);

// Media
$router->post('/wonders/{id}/photos', [MediaController::class, 'uploadPhoto']);
$router->post('/wonders/{id}/documents', [MediaController::class, 'uploadDocument']);

// Approvals & Admin
$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/settings', [AdminController::class, 'settings']);
$router->post('/admin/settings', [AdminController::class, 'saveSettings']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->post('/admin/users/{id}/role', [AdminController::class, 'changeUserRole']);
$router->get('/admin/logs', [AdminController::class, 'logs']);
$router->post('/admin/photos/{id}/approve', [AdminController::class, 'approvePhoto']);
$router->post('/admin/photos/{id}/reject', [AdminController::class, 'rejectPhoto']);
$router->post('/admin/wonders/{id}/approve', [AdminController::class, 'approveWonder']);

// Search / filter / stats / export / map
$router->get('/search', [WonderController::class, 'search']);
$router->get('/stats', [AdminController::class, 'stats']);
$router->get('/export/wonders.csv', [AdminController::class, 'exportWondersCsv']);
$router->get('/map', [WonderController::class, 'map']);

// Researcher dashboard: my submissions
$router->get('/my', [WonderController::class, 'my']);

// Dispatch request
// Normalize request path so routing works from subdirectories too
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
if ($scriptDir && $scriptDir !== '/') {
    if (strpos($requestPath, $scriptDir) === 0) {
        $requestPath = substr($requestPath, strlen($scriptDir)) ?: '/';
    }
}

$router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestPath);
