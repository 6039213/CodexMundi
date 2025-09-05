<?php


$env = [];
if (file_exists(__DIR__ . '/.env')) {
	foreach (file(__DIR__ . '/.env') as $line) {
		if (preg_match('/^\s*#/', $line) || !trim($line)) continue;
		[$k,$v] = array_map('trim', explode('=', $line, 2));
		$env[$k] = $v;
	}
}

return [
	'db' => [
		'host' => $env['DB_HOST'] ?? '127.0.0.1',
		'port' => (int)($env['DB_PORT'] ?? 3306),
		'database' => $env['DB_NAME'] ?? 'codex_mundi',
		'username' => $env['DB_USER'] ?? 'root',
		'password' => $env['DB_PASS'] ?? '',
		'charset' => 'utf8mb4',
		'options' => [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES => false,
		],
	],
	'app' => [
		'name' => 'Codex Mundi',
		'base_url' => '/',
		'debug' => true,
	],
];
