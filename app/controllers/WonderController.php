<?php
require_once __DIR__ . '/../models/Wonder.php';
require_once __DIR__ . '/../models/Media.php';
require_once __DIR__ . '/../lib/helpers.php';

class WonderController
{
	public static function index(): array {
		$opts = [
			'q' => $_GET['q'] ?? null,
			'continent' => $_GET['continent'] ?? null,
			'category' => $_GET['category'] ?? null,
			'exists_now' => isset($_GET['exists']) ? (int)$_GET['exists'] : null,
			'sort' => $_GET['sort'] ?? 'name',
			'page' => (int)($_GET['page'] ?? 1),
			'limit' => 12,
			'status' => 'approved',
		];
		return Wonder::list($opts);
	}

	public static function show(string $slug): ?array {
		$wonder = Wonder::findBySlug($slug);
		if (!$wonder || $wonder['status'] !== 'approved') { return null; }
		$media = Media::listForWonder((int)$wonder['id']);
		$related = Wonder::related((int)$wonder['id'], $wonder['continent'], $wonder['category']);
		return ['wonder' => $wonder, 'media' => $media, 'related' => $related];
	}

	public static function mapPoints(): void {
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(Wonder::mapPoints(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
}


