<?php
declare(strict_types=1);

namespace CodexMundi\Services;

use CodexMundi\Core\Database;
use PDO;

class Notifications {
    public static function notify(int $userId, string $message, ?string $link = null, string $type = 'info'): void {
        $db = Database::conn();
        $stmt = $db->prepare('INSERT INTO notifications (user_id,type,message,link,is_read,created_at) VALUES (?,?,?,?,0,?)');
        $stmt->execute([$userId, $type, $message, $link, date('c')]);
    }

    public static function notifyRole(string $roleName, string $message, ?string $link = null, string $type = 'info'): void {
        $db = Database::conn();
        $stmt = $db->prepare('SELECT u.id FROM users u JOIN roles r ON r.id=u.role_id WHERE r.name=?');
        $stmt->execute([$roleName]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $uid = (int)($row['id'] ?? 0);
            if ($uid) {
                self::notify($uid, $message, $link, $type);
            }
        }
    }

    public static function unreadFor(int $userId): array {
        $db = Database::conn();
        $stmt = $db->prepare('SELECT id,type,message,link,created_at FROM notifications WHERE user_id=? AND is_read=0 ORDER BY id DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function markAllRead(int $userId): void {
        $db = Database::conn();
        $db->prepare('UPDATE notifications SET is_read=1 WHERE user_id=?')->execute([$userId]);
    }
}
