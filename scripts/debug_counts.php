<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

$db = CodexMundi\Core\Database::conn();

$totalW = (int)$db->query('SELECT COUNT(*) FROM wonders')->fetchColumn();
$totalP = (int)$db->query('SELECT COUNT(*) FROM photos')->fetchColumn();
echo "wonders={$totalW} photos={$totalP}\n";

$stmt = $db->query('SELECT w.id,w.name,(SELECT COUNT(*) FROM photos p WHERE p.wonder_id=w.id) AS pc,(SELECT path FROM photos p WHERE p.wonder_id=w.id AND p.approved=1 ORDER BY id DESC LIMIT 1) AS cover FROM wonders w ORDER BY w.name LIMIT 10');
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['id'] . ' | ' . $row['name'] . ' | photos=' . $row['pc'] . ' | cover=' . ($row['cover'] ?? '') . "\n";
}


