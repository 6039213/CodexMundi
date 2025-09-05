<?php
require_once __DIR__ . '/../lib/helpers.php';
require_once __DIR__ . '/../lib/auth.php';
require_once __DIR__ . '/../controllers/UserController.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	UserController::updateRole();
} else {
	http_response_code(405);
	echo 'Method not allowed';
}


