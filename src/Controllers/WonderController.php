<?php
declare(strict_types=1);

namespace CodexMundi\Controllers;

use CodexMundi\Core\Database;
use PDO;

class WonderController extends BaseController {
    public function index(): void {
        $db = Database::conn();
        $stmt = $db->query('SELECT id, name, short_description, continent, type, exists_now, view_count, approved, (
            SELECT path FROM photos p WHERE p.wonder_id=wonders.id AND p.approved=1 ORDER BY id DESC LIMIT 1
        ) as cover FROM wonders ORDER BY name');
        $wonders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('wonders/index.php', compact('wonders'));
    }

    public function search(): void {
        $q = trim($_GET['q'] ?? '');
        $continent = trim($_GET['continent'] ?? '');
        $type = trim($_GET['type'] ?? '');
        $sort = trim($_GET['sort'] ?? 'name');
        $order = in_array($sort, ['name','year']) ? $sort : 'name';
        $db = Database::conn();
        $sql = 'SELECT id,name,short_description,continent,type,year,exists_now,approved FROM wonders WHERE 1=1';
        $params = [];
        if ($q !== '') { $sql .= ' AND name LIKE ?'; $params[] = "%$q%"; }
        if ($continent !== '') { $sql .= ' AND continent = ?'; $params[] = $continent; }
        if ($type !== '') { $sql .= ' AND type = ?'; $params[] = $type; }
        $sql .= ' ORDER BY ' . $order;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $wonders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('wonders/search.php', compact('wonders','q','continent','type','sort'));
    }

    public function show($id): void {
        $db = Database::conn();
        $stmt = $db->prepare('SELECT * FROM wonders WHERE id=?');
        $stmt->execute([(int)$id]);
        $wonder = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$wonder) { http_response_code(404); echo 'Niet gevonden'; return; }
        $db->prepare('UPDATE wonders SET view_count=view_count+1 WHERE id=?')->execute([(int)$id]);

        $photos = $db->prepare('SELECT * FROM photos WHERE wonder_id=? AND approved=1 ORDER BY id DESC');
        $photos->execute([(int)$id]);
        $photos = $photos->fetchAll(PDO::FETCH_ASSOC);

        $docs = $db->prepare('SELECT * FROM documents WHERE wonder_id=? ORDER BY id DESC');
        $docs->execute([(int)$id]);
        $docs = $docs->fetchAll(PDO::FETCH_ASSOC);

        $tags = \CodexMundi\Services\Tags::getTags((int)$id);
        $this->view('wonders/show.php', compact('wonder','photos','docs','tags'));
    }

    public function create(): void {
        $this->requireRole(['onderzoeker','beheerder']);
        $this->view('wonders/create.php');
    }

    public function store(): void {
        $this->requireRole(['onderzoeker','beheerder']);
        $db = Database::conn();
        $user = $_SESSION['user'];
        $stmt = $db->prepare('INSERT INTO wonders (name,short_description,year,continent,type,exists_now,myth,story,lat,lng,approved,created_by,updated_by,view_count,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute([
            trim($_POST['name'] ?? ''),
            trim($_POST['short_description'] ?? ''),
            $_POST['year'] !== '' ? (int)$_POST['year'] : null,
            trim($_POST['continent'] ?? ''),
            trim($_POST['type'] ?? ''),
            isset($_POST['exists_now']) ? 1 : 0,
            trim($_POST['myth'] ?? ''),
            trim($_POST['story'] ?? ''),
            $_POST['lat'] !== '' ? (float)$_POST['lat'] : null,
            $_POST['lng'] !== '' ? (float)$_POST['lng'] : null,
            0,
            (int)$user['id'],
            (int)$user['id'],
            0,
            date('c'),
            date('c')
        ]);
        \CodexMundi\Core\Audit::log((int)$user['id'], 'create', 'wonder', (int)$db->lastInsertId());
        $_SESSION['flash'] = ['type'=>'success','message'=>'Wereldwonder aangemaakt, wacht op goedkeuring.'];
        $this->redirect('/');
    }

    public function edit($id): void {
        $this->requireRole(['onderzoeker','beheerder','archivaris','redacteur']);
        $db = Database::conn();
        $stmt = $db->prepare('SELECT * FROM wonders WHERE id=?');
        $stmt->execute([(int)$id]);
        $wonder = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$wonder) { http_response_code(404); echo 'Niet gevonden'; return; }

        $user = $_SESSION['user'];
        if ($user['role'] === 'onderzoeker' && (int)$wonder['created_by'] !== (int)$user['id']) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Je mag alleen je eigen wonderen wijzigen.'];
            $this->redirect('/');
        }

        $tags = \CodexMundi\Services\Tags::getTags((int)$id);
        $this->view('wonders/edit.php', compact('wonder','tags'));
    }

    public function update($id): void {
        $this->requireRole(['onderzoeker','beheerder','archivaris','redacteur']);
        $db = Database::conn();
        $stmt = $db->prepare('SELECT * FROM wonders WHERE id=?');
        $stmt->execute([(int)$id]);
        $wonder = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$wonder) { http_response_code(404); echo 'Niet gevonden'; return; }

        $user = $_SESSION['user'];
        if ($user['role'] === 'onderzoeker' && (int)$wonder['created_by'] !== (int)$user['id']) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Je mag alleen je eigen wonderen wijzigen.'];
            $this->redirect('/');
        }

        // Archivaris limited fields
        if ($user['role'] === 'archivaris') {
            $fields = ['year','myth','story','exists_now','lat','lng'];
            $sql = 'UPDATE wonders SET year=?, myth=?, story=?, exists_now=?, lat=?, lng=?, updated_by=?, updated_at=?, approved=0 WHERE id=?';
            $params = [
                $_POST['year'] !== '' ? (int)$_POST['year'] : null,
                trim($_POST['myth'] ?? ''),
                trim($_POST['story'] ?? ''),
                isset($_POST['exists_now']) ? 1 : 0,
                $_POST['lat'] !== '' ? (float)$_POST['lat'] : null,
                $_POST['lng'] !== '' ? (float)$_POST['lng'] : null,
                (int)$user['id'],
                date('c'),
                (int)$id
            ];
            $db->prepare($sql)->execute($params);
        } else {
            $sql = 'UPDATE wonders SET name=?, short_description=?, year=?, continent=?, type=?, exists_now=?, myth=?, story=?, lat=?, lng=?, updated_by=?, updated_at=?, approved=0 WHERE id=?';
            $params = [
                trim($_POST['name'] ?? ''),
                trim($_POST['short_description'] ?? ''),
                $_POST['year'] !== '' ? (int)$_POST['year'] : null,
                trim($_POST['continent'] ?? ''),
                trim($_POST['type'] ?? ''),
                isset($_POST['exists_now']) ? 1 : 0,
                trim($_POST['myth'] ?? ''),
                trim($_POST['story'] ?? ''),
                $_POST['lat'] !== '' ? (float)$_POST['lat'] : null,
                $_POST['lng'] !== '' ? (float)$_POST['lng'] : null,
                (int)$user['id'],
                date('c'),
                (int)$id
            ];
            $db->prepare($sql)->execute($params);
        }
        \CodexMundi\Core\Audit::log((int)$user['id'], 'update', 'wonder', (int)$id);

        // Tags can be managed by redacteur or beheerder
        if (in_array($user['role'], ['redacteur','beheerder'], true)) {
            $tags = array_filter(array_map('trim', explode(',', (string)($_POST['tags'] ?? ''))));
            \CodexMundi\Services\Tags::setTags((int)$id, $tags);
        }

        $_SESSION['flash'] = ['type'=>'success','message'=>'Wijzigingen opgeslagen, wacht op goedkeuring.'];
        $this->redirect('/wonders/' . (int)$id);
    }

    public function destroy($id): void {
        $this->requireRole(['beheerder']);
        $db = Database::conn();
        $db->prepare('DELETE FROM wonders WHERE id=?')->execute([(int)$id]);
        \CodexMundi\Core\Audit::log((int)$_SESSION['user']['id'], 'delete', 'wonder', (int)$id);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Wereldwonder verwijderd.'];
        $this->redirect('/');
    }

    public function map(): void {
        $db = Database::conn();
        $stmt = $db->query('SELECT id,name,lat,lng,type,continent FROM wonders WHERE lat IS NOT NULL AND lng IS NOT NULL');
        $points = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('wonders/map.php', compact('points'));
    }

    public function my(): void {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            $_SESSION['flash'] = ['type'=>'error','message'=>'Log in om je bijdragen te zien.'];
            $this->redirect('/login');
        }
        $db = Database::conn();
        $stmt = $db->prepare('SELECT id,name,approved,updated_at FROM wonders WHERE created_by = ? ORDER BY updated_at DESC');
        $stmt->execute([(int)$user['id']]);
        $mine = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->view('wonders/my.php', compact('mine'));
    }
}
