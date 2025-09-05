<?php
require_once __DIR__ . '/../lib/db.php';

class User
{
	public static function findByEmail(string $email): ?array {
		$pdo = get_pdo();
		$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
		$stmt->execute([$email]);
		$row = $stmt->fetch();
		return $row ?: null;
	}

	public static function all(): array {
		$pdo = get_pdo();
		return $pdo->query('SELECT id, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
	}

	public static function updateRole(int $id, string $role): void {
		$pdo = get_pdo();
		$stmt = $pdo->prepare('UPDATE users SET role = ? WHERE id = ?');
		$stmt->execute([$role, $id]);
	}
}


