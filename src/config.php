<?php
declare(strict_types=1);

namespace CodexMundi;

class Config {
    public const DB_PATH = __DIR__ . '/../storage/codexmundi.sqlite';
    public const UPLOAD_DIR_PHOTOS = __DIR__ . '/../public/uploads/photos';
    public const UPLOAD_DIR_DOCS = __DIR__ . '/../public/uploads/documents';
    public const MAX_PHOTO_SIZE = 5 * 1024 * 1024; // 5MB
    public const MAX_DOC_SIZE = 10 * 1024 * 1024; // 10MB
    public const ALLOWED_PHOTO_TYPES = ['image/jpeg','image/png','image/webp'];
    public const ALLOWED_DOC_TYPES = ['application/pdf','text/plain'];
}

