<?php
// UI/Alert
// Params: $type ('success'|'error'|''), $message (string)
$type = $type ?? '';
$message = $message ?? '';
echo '<div class="alert ' . htmlspecialchars((string)$type) . '">' . htmlspecialchars((string)$message) . '</div>';

