<?php
declare(strict_types=1);

use CodexMundi\Core\Database;

require_once __DIR__ . '/../src/bootstrap.php';

function wikiPrimaryImage(string $title, string $lang = 'en'): ?string {
    $api = "https://{$lang}.wikipedia.org/w/api.php?action=query&titles="
        . rawurlencode($title)
        . "&prop=pageimages&format=json&pithumbsize=1600&origin=*";
    $json = @file_get_contents($api);
    if ($json === false) return null;
    $data = json_decode($json, true);
    if (!is_array($data) || empty($data['query']['pages'])) return null;
    foreach ($data['query']['pages'] as $page) {
        if (!empty($page['thumbnail']['source'])) {
            // Prefer original if available
            if (!empty($page['original']['source'])) {
                return (string)$page['original']['source'];
            }
            return (string)$page['thumbnail']['source'];
        }
        if (!empty($page['original']['source'])) {
            return (string)$page['original']['source'];
        }
    }
    return null;
}

$db = Database::conn();
$now = date('c');

$rows = $db->query(
    "SELECT w.id, w.name FROM wonders w WHERE NOT EXISTS (SELECT 1 FROM photos p WHERE p.wonder_id=w.id AND p.approved=1)"
)->fetchAll(PDO::FETCH_ASSOC);

$insPhoto = $db->prepare('INSERT INTO photos (wonder_id,path,title,approved,uploaded_by,created_at) VALUES (?,?,?,?,?,?)');
$setCover = $db->prepare('UPDATE wonders SET cover=? WHERE id=?');

$added = 0; $attempted = 0;
foreach ($rows as $r) {
    $attempted++;
    $title = (string)$r['name'];
    $img = wikiPrimaryImage($title, 'en') ?? wikiPrimaryImage($title, 'nl');
    if (!$img) {
        continue;
    }
    $wid = (int)$r['id'];
    $insPhoto->execute([$wid, $img, $title, 1, null, $now]);
    $setCover->execute([$img, $wid]);
    $added++;
}

echo "Attempted {$attempted}, added {$added} images" . PHP_EOL;


