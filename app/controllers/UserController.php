<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/auth.php';

class UserController
{
	public static function login(): ?string {
		verify_csrf();
		$email = trim($_POST['email'] ?? '');
		$pass = $_POST['password'] ?? '';
		if (!$email || !$pass) return 'Vul e-mail en wachtwoord in';
		// simple backoff
		sleep(1);
		if (auth_login($email, $pass)) {
			self::audit('login', ['email' => $email]);
			redirect('/public/dashboard/index.php');
		}
		return 'Onjuiste login';
	}

	public static function register(): ?string {
		verify_csrf();
		$email = trim($_POST['email'] ?? '');
		$pass = $_POST['password'] ?? '';
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pass) < 8) return 'Ongeldige invoer';
		$pdo = get_pdo();
		$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
		$stmt->execute([$email]);
		if ($stmt->fetch()) return 'E-mail bestaat al';
		$hash = password_hash($pass, PASSWORD_BCRYPT);
		$ins = $pdo->prepare('INSERT INTO users(email, password_hash, role) VALUES(?, ?, "researcher")');
		$ins->execute([$email, $hash]);
		self::audit('register', ['email' => $email]);
		return null;
	}

	public static function updateRole(): void {
		require_role(['admin']);
		verify_csrf();
		$id = (int)($_POST['id'] ?? 0);
		$role = $_POST['role'] ?? 'visitor';
		User::updateRole($id, $role);
		self::audit('role_update', ['user_id' => $id, 'role' => $role]);
		redirect('/public/dashboard/users.php');
	}

	private static function audit(string $action, array $changes): void {
		try {
			$pdo = get_pdo();
			$actor = current_user()['id'] ?? null;
			$stmt = $pdo->prepare('INSERT INTO audit_log(actor_id, entity, entity_id, action, changes) VALUES(?, ?, ?, ?, ?)');
			$stmt->execute([$actor, 'user', 0, $action, json_encode($changes, JSON_UNESCAPED_UNICODE)]);
		} catch (Throwable $e) { /* ignore */ }
	}
}


