<?php
require_once __DIR__ . '/../app/lib/auth.php';
auth_logout();
header('Location: /public/index.php');
exit;


