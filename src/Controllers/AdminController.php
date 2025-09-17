<?php
declare(strict_types=1);

namespace CodexMundi\Controllers;

use CodexMundi\Core\Database;
use PDO;

class AdminController extends BaseController {
    public function dashboard(): void {
        $this->requireRole(['redacteur','beheerder']);
        $db = Database::conn();
        $pendingPhotos = $db->query('SELECT photos.*, wonders.name AS wonder_name FROM photos JOIN wonders ON wonders.id=photos.wonder_id WHERE photos.approved=0 ORDER BY photos.id DESC')->fetchAll(PDO::FETCH_ASSOC);
        $pendingWonders = $db->query('SELECT id,name,updated_at FROM wonders WHERE approved=0 ORDER BY updated_at DESC')->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin/dashboard.php', compact('pendingPhotos','pendingWonders'));
    }

    public function approvePhoto($id): void {
        $this->requireRole(['redacteur','beheerder']);
        $db = Database::conn();
        $db->prepare('UPDATE photos SET approved=1 WHERE id=?')->execute([(int)$id]);
        try {
            // Notify uploader if available
            $q = $db->prepare('SELECT uploaded_by, wonder_id FROM photos WHERE id=?');
            $q->execute([(int)$id]);
            $row = $q->fetch(PDO::FETCH_ASSOC);
            if ($row && !empty($row['uploaded_by'])) {
                \CodexMundi\Services\Notifications::notify((int)$row['uploaded_by'], 'Je foto is goedgekeurd', '/wonders/' . (int)$row['wonder_id'], 'success');
            }
        } catch (\Throwable $e) {}
        \CodexMundi\Core\Audit::log((int)$_SESSION['user']['id'], 'approve', 'photo', (int)$id);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Foto goedgekeurd.'];
        $this->redirect('/admin');
    }

    public function rejectPhoto($id): void {
        $this->requireRole(['redacteur','beheerder']);
        $db = Database::conn();
        $db->prepare('DELETE FROM photos WHERE id=?')->execute([(int)$id]);
        \CodexMundi\Core\Audit::log((int)$_SESSION['user']['id'], 'reject', 'photo', (int)$id);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Foto afgekeurd en verwijderd.'];
        $this->redirect('/admin');
    }

    public function approveWonder($id): void {
        $this->requireRole(['redacteur','beheerder']);
        $db = Database::conn();
        $db->prepare('UPDATE wonders SET approved=1 WHERE id=?')->execute([(int)$id]);
        // Notify last editor/creator
        try {
            $q=$db->prepare('SELECT created_by, updated_by FROM wonders WHERE id=?');
            $q->execute([(int)$id]);
            $row=$q->fetch(PDO::FETCH_ASSOC);
            $notifyUserId = (int)($row['updated_by'] ?? 0) ?: (int)($row['created_by'] ?? 0);
            if ($notifyUserId) { \CodexMundi\Services\Notifications::notify($notifyUserId, 'Je wereldwonder is goedgekeurd', '/wonders/' . (int)$id, 'success'); }
        } catch (\Throwable $e) {}
        \CodexMundi\Core\Audit::log((int)$_SESSION['user']['id'], 'approve', 'wonder', (int)$id);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Wereldwonder goedgekeurd.'];
        $this->redirect('/admin');
    }

    public function stats(): void {
        $this->requireRole(['beheerder','redacteur']);
        $db = Database::conn();
        $perContinent = $db->query('SELECT continent, COUNT(*) as c FROM wonders GROUP BY continent')->fetchAll(PDO::FETCH_ASSOC);
        $latestEdited = $db->query('SELECT id,name,updated_at FROM wonders ORDER BY updated_at DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
        $mostViewed = $db->query('SELECT id,name,view_count FROM wonders ORDER BY view_count DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin/stats.php', compact('perContinent','latestEdited','mostViewed'));
    }

    public function exportWondersCsv(): void {
        $this->requireRole(['beheerder']);
        $db = Database::conn();
        $rows = $db->query('SELECT id,name,year,continent,type,exists_now,lat,lng,approved,updated_at FROM wonders ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="wonders.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($rows[0] ?? ["id","name","year","continent","type","exists_now","lat","lng","approved","updated_at"]));
        foreach ($rows as $r) { fputcsv($out, $r); }
        fclose($out);
        exit;
    }

    public function exportMyWondersCsv(): void {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Log in om te exporteren.'];
            $this->redirect('/login');
        }
        $db = Database::conn();
        $stmt = $db->prepare('SELECT id,name,year,continent,type,exists_now,lat,lng,approved,updated_at FROM wonders WHERE created_by=? ORDER BY updated_at DESC');
        $stmt->execute([(int)$user['id']]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="my_wonders.csv"');
        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($rows[0] ?? ["id","name","year","continent","type","exists_now","lat","lng","approved","updated_at"]));
        foreach ($rows as $r) { fputcsv($out, $r); }
        fclose($out);
        exit;
    }

    public function settings(): void {
        $this->requireRole(['beheerder']);
        $this->view('admin/settings.php');
    }

    public function saveSettings(): void {
        $this->requireRole(['beheerder']);
        \CodexMundi\Services\Settings::set('max_photo_size', (string)($_POST['max_photo_size'] ?? ''));
        \CodexMundi\Services\Settings::set('max_doc_size', (string)($_POST['max_doc_size'] ?? ''));
        \CodexMundi\Services\Settings::set('allowed_photo_types', (string)($_POST['allowed_photo_types'] ?? ''));
        \CodexMundi\Services\Settings::set('allowed_doc_types', (string)($_POST['allowed_doc_types'] ?? ''));
        \CodexMundi\Services\Settings::set('login_max_attempts_per_minute', (string)($_POST['login_max_attempts_per_minute'] ?? ''));
        \CodexMundi\Services\Settings::set('password_min_length', (string)($_POST['password_min_length'] ?? ''));
        $_SESSION['flash'] = ['type'=>'success','message'=>'Instellingen opgeslagen'];
        $this->redirect('/admin/settings');
    }

    public function users(): void {
        $this->requireRole(['beheerder']);
        $db = Database::conn();
        $users = $db->query('SELECT users.id, users.name, users.email, roles.name AS role FROM users JOIN roles ON roles.id=users.role_id ORDER BY users.id DESC')->fetchAll(PDO::FETCH_ASSOC);
        $roles = $db->query('SELECT name FROM roles ORDER BY id')->fetchAll(PDO::FETCH_COLUMN);
        $this->view('admin/users.php', compact('users','roles'));
    }

    public function createUser(): void {
        $this->requireRole(['beheerder']);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $roleName = trim($_POST['role'] ?? 'bezoeker');
        $minLen = \CodexMundi\Services\Settings::passwordMinLength();
        if ($name === '' || $email === '' || strlen($password) < $minLen) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Vul naam/email in en wachtwoord min. ' . $minLen . ' tekens.'];
            $this->redirect('/admin/users');
        }
        $db = Database::conn();
        $stmt = $db->prepare('SELECT id FROM roles WHERE name=?');
        $stmt->execute([$roleName]);
        $roleId = $stmt->fetchColumn();
        if (!$roleId) { $roleId = $db->query("SELECT id FROM roles WHERE name='bezoeker'")->fetchColumn(); }
        try {
            $ins = $db->prepare('INSERT INTO users (email,password,name,role_id,created_at) VALUES (?,?,?,?,?)');
            $ins->execute([$email, password_hash($password, PASSWORD_DEFAULT), $name, (int)$roleId, date('c')]);
            $_SESSION['flash'] = ['type'=>'success','message'=>'Gebruiker aangemaakt'];
        } catch (\Throwable $e) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Kon gebruiker niet aanmaken (email uniek?).'];
        }
        $this->redirect('/admin/users');
    }

    public function changeUserRole($id): void {
        $this->requireRole(['beheerder']);
        $role = trim($_POST['role'] ?? '');
        $db = Database::conn();
        $stmt = $db->prepare('SELECT id FROM roles WHERE name=?');
        $stmt->execute([$role]);
        $roleId = $stmt->fetchColumn();
        if (!$roleId) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Ongeldige rol'];
            $this->redirect('/admin/users');
        }
        $db->prepare('UPDATE users SET role_id=? WHERE id=?')->execute([(int)$roleId, (int)$id]);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Rol bijgewerkt'];
        $this->redirect('/admin/users');
    }

    public function logs(): void {
        $this->requireRole(['beheerder']);
        $db = Database::conn();
        $rows = $db->query('SELECT a.id, u.name as user_name, a.action, a.entity, a.entity_id, a.created_at, a.details FROM audit_logs a LEFT JOIN users u ON u.id=a.user_id ORDER BY a.id DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
        $this->view('admin/logs.php', ['logs' => $rows]);
    }
}
