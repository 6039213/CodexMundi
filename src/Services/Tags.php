<?php
declare(strict_types=1);

namespace CodexMundi\Services;

use CodexMundi\Core\Database;
use PDO;

class Tags {
    public static function setTags(int $wonderId, array $names): void {
        $db = Database::conn();
        // normalize names
        $names = array_values(array_unique(array_filter(array_map(function($n){
            return strtolower(trim((string)$n));
        }, $names))));

        // ensure tags exist
        $tagIds = [];
        foreach ($names as $name) {
            $stmt = $db->prepare('INSERT OR IGNORE INTO tags (name) VALUES (?)');
            $stmt->execute([$name]);
        }
        // fetch ids
        if ($names) {
            $in = implode(',', array_fill(0, count($names), '?'));
            $stmt = $db->prepare('SELECT id,name FROM tags WHERE name IN ('.$in.')');
            $stmt->execute($names);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $r) { $tagIds[$r['name']] = (int)$r['id']; }
        }

        // replace assignments
        $db->prepare('DELETE FROM wonder_tags WHERE wonder_id=?')->execute([$wonderId]);
        foreach ($names as $name) {
            $tid = $tagIds[$name] ?? null;
            if ($tid) {
                $db->prepare('INSERT INTO wonder_tags (wonder_id, tag_id) VALUES (?,?)')->execute([$wonderId, $tid]);
            }
        }
    }

    public static function getTags(int $wonderId): array {
        $db = Database::conn();
        $stmt = $db->prepare('SELECT t.name FROM tags t JOIN wonder_tags wt ON wt.tag_id=t.id WHERE wt.wonder_id=? ORDER BY t.name');
        $stmt->execute([$wonderId]);
        return array_map('strval', array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'name'));
    }
}
