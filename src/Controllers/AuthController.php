<?php
declare(strict_types=1);

namespace CodexMundi\Controllers;

use CodexMundi\Core\Database;
use PDO;

class AuthController extends BaseController {
    public function showLogin(): void {
        $this->view('auth/login.php');
    }

    public function login(): void {
        // Simple rate-limit: 5 attempts per minute per session
        $now = time();
        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? [];
        // purge old
        $_SESSION['login_attempts'] = array_values(array_filter($_SESSION['login_attempts'], fn($t) => ($now - (int)$t) < 60));
        if (count($_SESSION['login_attempts']) >= 5) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Te veel pogingen. Probeer over een minuut opnieuw.'];
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $db = Database::conn();
        $stmt = $db->prepare('SELECT users.*, roles.name as role FROM users JOIN roles ON roles.id=users.role_id WHERE email=?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => (int)$user['id'],
                'email' => $user['email'],
                'name' => $user['name'],
                'role' => $user['role'],
            ];
            $_SESSION['login_attempts'] = [];
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Welkom terug!'];
            // Redirect admins/editors to dashboard; others to home
            if (in_array($user['role'], ['beheerder','redacteur'], true)) {
                $this->redirect('/admin');
            }
            $this->redirect('/');
        }
        $_SESSION['login_attempts'][] = $now;
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Ongeldige inloggegevens'];
        $this->redirect('/login');
    }

    public function logout(): void {
        unset($_SESSION['user']);
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Uitgelogd'];
        $this->redirect('/');
    }

    public function showRegister(): void {
        $this->view('auth/register.php');
    }

    public function register(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($name === '' || $email === '' || strlen($password) < 6) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Vul alle velden in (min. 6 tekens wachtwoord).'];
            $this->redirect('/register');
        }
        $db = Database::conn();
        // default role: bezoeker
        $roleId = (int)$db->query("SELECT id FROM roles WHERE name='bezoeker'")->fetchColumn();
        $stmt = $db->prepare('INSERT INTO users (email,password,name,role_id,created_at) VALUES (?,?,?,?,?)');
        try {
            $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $name, $roleId, date('c')]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Account aangemaakt. Log nu in.'];
            $this->redirect('/login');
        } catch (\Throwable $e) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Kon account niet aanmaken (email al in gebruik?).'];
            $this->redirect('/register');
        }
    }
}
