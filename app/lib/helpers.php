<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

function e(string $value): string {
	return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string {
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function csrf_field(): string {
	$token = csrf_token();
	return '<input type="hidden" name="csrf" value="' . e($token) . '">';
}

function verify_csrf(): void {
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$token = $_POST['csrf'] ?? '';
		if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
			http_response_code(400);
			exit('Invalid CSRF token');
		}
	}
}

function redirect(string $path): void {
	header('Location: ' . $path);
	exit;
}

function current_user(): ?array {
	return $_SESSION['user'] ?? null;
}

function is_role(string $role): bool {
	$user = current_user();
	return $user && $user['role'] === $role;
}

function require_role(array $roles): void {
	$user = current_user();
	if (!$user || !in_array($user['role'], $roles, true)) {
		http_response_code(403);
		exit('Forbidden');
	}
}
