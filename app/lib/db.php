<?php

// ... existing code ...
function get_pdo(): PDO {
	static $pdo = null;
	if ($pdo instanceof PDO) {
		return $pdo;
	}

	$config = require __DIR__ . '/../config.php';
	$db = $config['db'];

	$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $db['host'], $db['port'], $db['database'], $db['charset']);
	try {
		$pdo = new PDO($dsn, $db['username'], $db['password'], $db['options']);
	} catch (PDOException $e) {
		// Unknown database -> create it and bootstrap schema/seed
		if (strpos($e->getMessage(), 'Unknown database') !== false || (int)$e->getCode() === 1049) {
			$dsnNoDb = sprintf('mysql:host=%s;port=%d;charset=%s', $db['host'], $db['port'], $db['charset']);
			$tmp = new PDO($dsnNoDb, $db['username'], $db['password'], $db['options']);
			$tmp->exec("CREATE DATABASE IF NOT EXISTS `" . str_replace('`','', $db['database']) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
			$pdo = new PDO($dsn, $db['username'], $db['password'], $db['options']);
			// Bootstrap schema and seed on first creation
			try {
				$schema = @file_get_contents(__DIR__ . '/../../database/schema.sql');
				if ($schema) { $pdo->exec($schema); }
				$seed = @file_get_contents(__DIR__ . '/../../database/seed.sql');
				if ($seed) { $pdo->exec($seed); }
			} catch (Throwable $ignored) { /* ignore bootstrap errors */ }
		} else {
			throw $e;
		}
	}

	// Ensure SQL mode sane defaults if needed (optional, harmless if lacking perms)
	try {
		$pdo->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
		$pdo->exec("SET sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
	} catch (Throwable $e) {
		// ignore
	}

	return $pdo;
}
