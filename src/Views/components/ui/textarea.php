<?php
// UI/Textarea component
// Params: $name (req), $id (opt), $value (opt), $placeholder (opt), $label (opt), $rows (opt)
$name = $name ?? '';
$id = $id ?? $name;
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$label = $label ?? '';
$rows = isset($rows) ? (int)$rows : 4;
if ($label) echo '<label for="' . htmlspecialchars((string)$id) . '">' . htmlspecialchars((string)$label) . '</label>';
echo '<textarea name="' . htmlspecialchars((string)$name) . '" id="' . htmlspecialchars((string)$id) . '" rows="' . $rows . '"'
   . ($placeholder!=='' ? ' placeholder="' . htmlspecialchars((string)$placeholder) . '"' : '')
   . '>' . htmlspecialchars((string)$value) . '</textarea>';

