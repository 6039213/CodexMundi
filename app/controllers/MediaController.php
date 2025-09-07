<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../lib/db.php';

class MediaController
{
	public static function upload(): ?string {
		require_role(['researcher','editor','admin']);
		verify_csrf();
		$allowed = ['image/jpeg','image/png','image/webp','application/pdf'];
		if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) return 'Upload mislukt';
		$f = $_FILES['file'];
		if (!in_array($f['type'], $allowed, true)) return 'Mime-type niet toegestaan';
		if ($f['size'] > 5*1024*1024) return 'Bestand te groot (max 5MB)';
		$wonderId = (int)($_POST['wonder_id'] ?? 0);
		$ext = pathinfo($f['name'], PATHINFO_EXTENSION) ?: 'bin';
		$slug = $_POST['slug'] ?? ('wonder-' . $wonderId);
		$destDir = __DIR__ . '/../../public/assets/img/wonders';
		@mkdir($destDir, 0777, true);
		$destRel = '/assets/img/wonders/' . $slug . '-' . time() . '.' . $ext;
		$destAbs = __DIR__ . '/../../public/' . ltrim($destRel, '/');
		if (!move_uploaded_file($f['tmp_name'], $destAbs)) return 'Kon bestand niet opslaan';
		$pdo = get_pdo();
		$stmt = $pdo->prepare('INSERT INTO media(wonder_id,type,url,mime,size,status,created_by) VALUES(?,?,?,?,?,"pending",?)');
		$stmt->execute([$wonderId, (strpos($f['type'],'image')===0?'image':'document'), $destRel, $f['type'], (int)$f['size'], current_user()['id'] ?? null]);
		return null;
	}
}


