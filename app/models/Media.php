<?php
require_once __DIR__ . '/../lib/db.php';

class Media
{
	public static function listForWonder(int $wonderId): array {
		$pdo = get_pdo();
		$stmt = $pdo->prepare("SELECT * FROM media WHERE wonder_id = ? AND status='approved' ORDER BY created_at DESC");
		$stmt->execute([$wonderId]);
		return $stmt->fetchAll();
	}
}


