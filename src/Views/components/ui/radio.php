<?php
// UI/Radio component
// Params: $name (req), $id (opt), $value (req), $checked (bool), $label (opt)
$name = $name ?? '';
$id = $id ?? ($name . '_' . ($value ?? 'val'));
$value = $value ?? '';
$checked = !empty($checked);
$label = $label ?? '';
echo '<label>';
echo '<input type="radio" name="' . htmlspecialchars((string)$name) . '" id="' . htmlspecialchars((string)$id) . '" value="' . htmlspecialchars((string)$value) . '"' . ($checked ? ' checked' : '') . ' /> ';
echo htmlspecialchars((string)$label);
echo '</label>';

