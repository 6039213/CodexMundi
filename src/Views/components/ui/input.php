<?php
// UI/Input component
// Params: $name (req), $id (optional), $type (default text), $value, $placeholder, $required (bool), $disabled (bool), $label
$name = $name ?? '';
$id = $id ?? $name;
$type = $type ?? 'text';
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$required = !empty($required);
$disabled = !empty($disabled);
$label = $label ?? '';
if ($label) {
  echo '<label for="' . htmlspecialchars((string)$id) . '">' . htmlspecialchars((string)$label) . '</label>';
}
echo '<input name="' . htmlspecialchars((string)$name) . '" id="' . htmlspecialchars((string)$id) . '" type="' . htmlspecialchars((string)$type) . '"'
    . ($value!=='' ? ' value="' . htmlspecialchars((string)$value) . '"' : '')
    . ($placeholder!=='' ? ' placeholder="' . htmlspecialchars((string)$placeholder) . '"' : '')
    . ($required ? ' required' : '')
    . ($disabled ? ' disabled' : '')
    . ' />';

