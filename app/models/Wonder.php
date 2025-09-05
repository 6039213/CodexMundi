<?php
require_once __DIR__ . '/../lib/db.php';

class Wonder
{
	public static function list(array $opts = []): array {
		$pdo = get_pdo();
		$page = max(1, (int)($opts['page'] ?? 1));
		$limit = max(1, min(60, (int)($opts['limit'] ?? 12)));
		$offset = ($page - 1) * $limit;
		$where = [];
		$params = [];
		if (!empty($opts['q'])) {
			$where[] = '(title LIKE ? OR country LIKE ? OR summary LIKE ?)';
			$q = '%' . $opts['q'] . '%';
			$params[] = $q; $params[] = $q; $params[] = $q;
		}
		if (!empty($opts['continent'])) { $where[] = 'continent = ?'; $params[] = $opts['continent']; }
		if (!empty($opts['category'])) { $where[] = 'category = ?'; $params[] = $opts['category']; }
		if (isset($opts['exists_now']) && $opts['exists_now'] !== '') { $where[] = 'exists_now = ?'; $params[] = (int)$opts['exists_now']; }
		if (!empty($opts['status'])) { $where[] = 'status = ?'; $params[] = $opts['status']; }
		$sqlWhere = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
		$sort = $opts['sort'] ?? 'name';
		$orderBy = 'title ASC';
		if ($sort === 'year') { $orderBy = 'year_built ASC'; }
		if ($sort === 'continent') { $orderBy = 'continent ASC, title ASC'; }
		$countStmt = $pdo->prepare("SELECT COUNT(*) AS c FROM wonders $sqlWhere");
		$countStmt->execute($params);
		$total = (int)$countStmt->fetchColumn();
		$sql = "SELECT * FROM wonders $sqlWhere ORDER BY $orderBy LIMIT $limit OFFSET $offset";
		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);
		$rows = $stmt->fetchAll();
		return ['items' => $rows, 'total' => $total, 'page' => $page, 'limit' => $limit];
	}

	public static function featured(int $limit = 6): array {
		$pdo = get_pdo();
		$stmt = $pdo->prepare("SELECT * FROM wonders WHERE status='approved' ORDER BY created_at DESC LIMIT ?");
		$stmt->bindValue(1, $limit, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

    public static function countsByContinent(): array {
        $pdo = get_pdo();
        $stmt = $pdo->query("SELECT continent, COUNT(*) c FROM wonders WHERE status='approved' GROUP BY continent");
        $out = [];
        foreach ($stmt->fetchAll() as $r) { $out[$r['continent']] = (int)$r['c']; }
        return $out;
    }

	public static function findBySlug(string $slug): ?array {
		$pdo = get_pdo();
		$stmt = $pdo->prepare('SELECT * FROM wonders WHERE slug = ? LIMIT 1');
		$stmt->execute([$slug]);
		$row = $stmt->fetch();
		return $row ?: null;
	}

	public static function related(int $id, string $continent, string $category, int $limit = 3): array {
		$pdo = get_pdo();
		$stmt = $pdo->prepare('SELECT * FROM wonders WHERE id <> ? AND status="approved" AND (continent = ? OR category = ?) ORDER BY RAND() LIMIT ?');
		$stmt->bindValue(1, $id, PDO::PARAM_INT);
		$stmt->bindValue(2, $continent, PDO::PARAM_STR);
		$stmt->bindValue(3, $category, PDO::PARAM_STR);
		$stmt->bindValue(4, $limit, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public static function mapPoints(): array {
		$pdo = get_pdo();
		$stmt = $pdo->query("SELECT id, slug, title, lat, lng, continent, category FROM wonders WHERE status='approved' AND lat IS NOT NULL AND lng IS NOT NULL");
		return $stmt->fetchAll();
	}
}


