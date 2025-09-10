<?php
declare(strict_types=1);

namespace CodexMundi\Services;

use CodexMundi\Core\Database;
use CodexMundi\Config;

class Settings {
    public static function get(string $key, ?string $default = null): ?string {
        $db = Database::conn();
        $stmt = $db->prepare('SELECT value FROM settings WHERE key=?');
        $stmt->execute([$key]);
        $v = $stmt->fetchColumn();
        return $v === false ? $default : (string)$v;
    }

    public static function set(string $key, string $value): void {
        $db = Database::conn();
        $stmt = $db->prepare('INSERT INTO settings (key,value) VALUES (?,?) ON CONFLICT(key) DO UPDATE SET value=excluded.value');
        $stmt->execute([$key,$value]);
    }

    public static function maxPhotoSize(): int {
        return (int) (self::get('max_photo_size', (string)Config::MAX_PHOTO_SIZE));
    }
    public static function maxDocSize(): int {
        return (int) (self::get('max_doc_size', (string)Config::MAX_DOC_SIZE));
    }
    public static function allowedPhotoTypes(): array {
        $val = self::get('allowed_photo_types', implode(',', Config::ALLOWED_PHOTO_TYPES));
        return array_values(array_filter(array_map('trim', explode(',', (string)$val))));
    }
    public static function allowedDocTypes(): array {
        $val = self::get('allowed_doc_types', implode(',', Config::ALLOWED_DOC_TYPES));
        return array_values(array_filter(array_map('trim', explode(',', (string)$val))));
    }
}

