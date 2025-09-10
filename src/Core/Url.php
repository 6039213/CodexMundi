<?php
declare(strict_types=1);

namespace CodexMundi\Core;

class Url {
    public static function resolveMedia(string $path, string $base = ''): string {
        $path = trim($path);
        if ($path === '') return $path;
        // Local path under public
        if (isset($path[0]) && $path[0] === '/') {
            return $base . $path;
        }
        // Remote URLs
        if (stripos($path, 'http://') === 0 || stripos($path, 'https://') === 0) {
            // Special handling for Wikimedia Commons Special:FilePath
            $parts = parse_url($path);
            if (!empty($parts['host']) && strtolower($parts['host']) === 'commons.wikimedia.org') {
                if (!empty($parts['path']) && stripos($parts['path'], '/wiki/Special:FilePath/') === 0) {
                    // Ensure direct file delivery
                    if (empty($parts['query'])) {
                        return $path . '?download';
                    }
                }
            }
            return $path;
        }
        return $path;
    }
}

