<?php
declare(strict_types=1);

namespace CodexMundi\Core;

use PDO;
use CodexMundi\Config;

class Database {
    private static ?PDO $pdo = null;

    public static function conn(): PDO {
        if (self::$pdo === null) {
            // Ensure the PDO SQLite driver is available, otherwise show a friendly guide
            if (!\extension_loaded('pdo_sqlite')) {
                self::renderMissingSqliteDriver();
                // renderMissingSqliteDriver() will exit; this is for static analyzers
                throw new \RuntimeException('PDO SQLite driver not available');
            }
            $dir = dirname(Config::DB_PATH);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $dsn = 'sqlite:' . Config::DB_PATH;
            self::$pdo = new PDO($dsn);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->exec('PRAGMA foreign_keys = ON;');
        }
        return self::$pdo;
    }

    /**
     * Output a styled, actionable message to enable SQLite on Laragon/PHP.
     * This avoids a raw fatal error when the driver is missing.
     */
    private static function renderMissingSqliteDriver(): void {
        http_response_code(500);
        $css = '/assets/app.css';
        $phpVersion = PHP_VERSION;
        $ini = php_ini_loaded_file() ?: '(onbekend)';
        $path = Config::DB_PATH;
        echo "<!doctype html><html lang=\"nl\"><head><meta charset=\"utf-8\"><meta name=\"viewport\" content=\"width=device-width, initial-scale=1\"><title>Configuratie vereist — SQLite driver</title><link rel=\"stylesheet\" href=\"{$css}\"></head><body class=\"container\">";
        echo "<div class=\"card\"><h1>SQLite driver ontbreekt</h1><p>De PDO SQLite driver is niet beschikbaar in PHP. Hierdoor kan Codex Mundi geen verbinding maken met de database (<code>sqlite:{$path}</code>).</p>";
        echo "<h2>Oplossing (Laragon)</h2><ol>
                <li>Open Laragon → Menu → PHP → php.ini</li>
                <li>Zoek en zet aan (verwijder ; indien aanwezig):<pre><code>extension=sqlite3
extension=pdo_sqlite</code></pre></li>
                <li>Herstart Apache/Nginx of herstart Laragon volledig.</li>
            </ol>";
        echo "<h3>Technische details</h3><ul>
                <li>PHP versie: <strong>{$phpVersion}</strong></li>
                <li>php.ini: <strong>{$ini}</strong></li>
                <li>DB pad: <strong>{$path}</strong></li>
            </ul>";
        echo "<p>Na het inschakelen, ververs deze pagina.</p></div>";
        echo "</body></html>";
        exit;
    }
}
