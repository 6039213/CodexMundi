<?php
declare(strict_types=1);

namespace CodexMundi\Controllers;

use CodexMundi\Config;
use CodexMundi\Core\Database;
use CodexMundi\Services\Settings;
use CodexMundi\Core\Audit;

class MediaController extends BaseController {
    public function uploadPhoto($id): void {
        $this->requireRole(['onderzoeker','beheerder']);
        $url = trim((string)($_POST['photo_url'] ?? ''));
        $db = Database::conn();

        if ($url !== '') {
            if (!preg_match('~^https?://~i', $url)) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Ongeldige URL.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            if (!preg_match('~\.(jpe?g|png|webp|gif|svg)(\?.*)?$~i', parse_url($url, PHP_URL_PATH) ?? '')) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'URL lijkt geen afbeelding te zijn.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            $stmt = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');
            $stmt->execute([(int)$id, $url, trim($_POST['title'] ?? ''), 0, (int)$_SESSION['user']['id'], date('c')]);
        } else {
            if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Geen foto ontvangen.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            $file = $_FILES['photo'];
            if ($file['size'] > Settings::maxPhotoSize()) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Bestand te groot.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (!in_array($mime, Settings::allowedPhotoTypes(), true)) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Ongeldig bestandsformaat.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            if (!is_dir(Config::UPLOAD_DIR_PHOTOS)) {
                mkdir(Config::UPLOAD_DIR_PHOTOS, 0777, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'photo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $target = Config::UPLOAD_DIR_PHOTOS . '/' . $filename;
            move_uploaded_file($file['tmp_name'], $target);
            $stmt = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');
            $stmt->execute([(int)$id, '/uploads/photos/' . $filename, trim($_POST['title'] ?? ''), 0, (int)$_SESSION['user']['id'], date('c')]);
        }
        $photoId = (int)$db->lastInsertId();
        Audit::log((int)$_SESSION['user']['id'], 'upload', 'photo', $photoId, 'wonder_id='.(int)$id);
        try {
            $link = '/wonders/' . (int)$id;
            \CodexMundi\Services\Notifications::notify((int)$_SESSION['user']['id'], 'Foto ingestuurd, wacht op beoordeling', $link, 'info');
            \CodexMundi\Services\Notifications::notifyRole('redacteur', 'Nieuwe foto ter beoordeling', $link, 'info');
            \CodexMundi\Services\Notifications::notifyRole('beheerder', 'Nieuwe foto ter beoordeling', $link, 'info');
        } catch (\Throwable $e) {}
        $_SESSION['flash'] = ['type'=>'success','message'=>'Foto geǬpload, wacht op goedkeuring.'];
        $this->redirect('/wonders/' . (int)$id);
    }

    public function uploadDocument($id): void {
        $this->requireRole(['archivaris','onderzoeker','beheerder']);
        $url = trim((string)($_POST['document_url'] ?? ''));
        $db = Database::conn();
        if ($url !== '') {
            if (!preg_match('~^https?://~i', $url)) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Ongeldige URL.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            $stmt = $db->prepare('INSERT INTO documents (wonder_id,path,title,uploaded_by,created_at) VALUES (?,?,?,?,?)');
            $stmt->execute([(int)$id, $url, trim($_POST['title'] ?? ''), (int)$_SESSION['user']['id'], date('c')]);
        } else {
            if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Geen document ontvangen.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            $file = $_FILES['document'];
            if ($file['size'] > Settings::maxDocSize()) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Bestand te groot.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
            if (!in_array($mime, Settings::allowedDocTypes(), true)) {
                $_SESSION['flash'] = ['type'=>'error','message'=>'Ongeldig bestandsformaat.'];
                $this->redirect('/wonders/' . (int)$id);
            }
            if (!is_dir(Config::UPLOAD_DIR_DOCS)) {
                mkdir(Config::UPLOAD_DIR_DOCS, 0777, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'doc_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $target = Config::UPLOAD_DIR_DOCS . '/' . $filename;
            move_uploaded_file($file['tmp_name'], $target);
            $stmt = $db->prepare('INSERT INTO documents (wonder_id,path,title,uploaded_by,created_at) VALUES (?,?,?,?,?)');
            $stmt->execute([(int)$id, '/uploads/documents/' . $filename, trim($_POST['title'] ?? ''), (int)$_SESSION['user']['id'], date('c')]);
        }
        Audit::log((int)$_SESSION['user']['id'], 'upload', 'document', (int)$db->lastInsertId(), 'wonder_id='.(int)$id);
        $_SESSION['flash'] = ['type'=>'success','message'=>'Document geǬpload.'];
        $this->redirect('/wonders/' . (int)$id);
    }
}
