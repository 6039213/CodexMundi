<?php
// UI/Switch component (checkbox)
// Params: $name (req), $id (opt), $checked (bool), $label (opt)
$name = $name ?? '';
$id = $id ?? $name;
$checked = !empty($checked);
$label = $label ?? '';
echo '<label class="switch">';
echo '<input type="checkbox" role="switch" name="' . htmlspecialchars((string)$name) . '" id="' . htmlspecialchars((string)$id) . '"' . ($checked ? ' checked' : '') . ' /> ';
echo htmlspecialchars((string)$label);
echo '</label>';

