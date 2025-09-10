<?php
declare(strict_types=1);

namespace CodexMundi\Core;

class Audit {
    public static function log(?int $userId, string $action, string $entity, ?int $entityId = null, ?string $details = null): void {
        $db = Database::conn();
        $stmt = $db->prepare('INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at, details) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$userId, $action, $entity, $entityId, date('c'), $details]);
    }
}

