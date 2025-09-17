<?php
// UI/Checkbox component
// Params: $name (req), $id (opt), $checked (bool), $label (opt)
$name = $name ?? '';
$id = $id ?? $name;
$checked = !empty($checked);
$label = $label ?? '';
echo '<label>';
echo '<input type="checkbox" name="' . htmlspecialchars((string)$name) . '" id="' . htmlspecialchars((string)$id) . '"' . ($checked ? ' checked' : '') . ' /> ';
echo htmlspecialchars((string)$label);
echo '</label>';

