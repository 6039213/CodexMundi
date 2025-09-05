<?php

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

function auth_login(string $email, string $password): bool {
	$pdo = get_pdo();
	$stmt = $pdo->prepare('SELECT id, email, password_hash, role FROM users WHERE email = ? LIMIT 1');
	$stmt->execute([$email]);
	$user = $stmt->fetch();
	if (!$user) { return false; }
	$stored = (string)$user['password_hash'];
	if (str_starts_with($stored, '$needs_hash$')) {
		$plain = substr($stored, strlen('$needs_hash$'));
		if (hash_equals($plain, $password)) {
			$newHash = password_hash($password, PASSWORD_BCRYPT);
			$upd = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
			$upd->execute([$newHash, (int)$user['id']]);
			$stored = $newHash;
		}
	}
	if (password_verify($password, $stored)) {
		$_SESSION['user'] = [
			'id' => (int)$user['id'],
			'email' => $user['email'],
			'role' => $user['role'],
		];
		return true;
	}
	return false;
}

function auth_logout(): void {
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_destroy();
}

function require_auth(): void {
        if (!current_user()) {
                redirect('/login.php');
        }
}

function require_role_or_redirect(array $roles): void {
	$user = current_user();
	if (!$user || !in_array($user['role'], $roles, true)) {
		http_response_code(403);
		exit('Forbidden');
	}
}
