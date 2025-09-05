<?php
require_once __DIR__ . '/../lib/db.php';

class Tag
{
	public static function forWonder(int $wonderId): array {
		$pdo = get_pdo();
		$stmt = $pdo->prepare('SELECT t.* FROM wonder_tags wt JOIN tags t ON t.id = wt.tag_id WHERE wt.wonder_id = ?');
		$stmt->execute([$wonderId]);
		return $stmt->fetchAll();
	}
}


