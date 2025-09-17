<?php
// UI/Select component
// Params: $name (req), $id (opt), $options (array), $label (opt)
$name = $name ?? '';
$id = $id ?? $name;
$options = $options ?? [];
$label = $label ?? '';
if ($label) echo '<label for="' . htmlspecialchars((string)$id) . '">' . htmlspecialchars((string)$label) . '</label>';
echo '<select name="' . htmlspecialchars((string)$name) . '" id="' . htmlspecialchars((string)$id) . '">';
foreach ($options as $opt) {
  if (is_array($opt)) {
    $val = (string)($opt['value'] ?? '');
    $lab = (string)($opt['label'] ?? $val);
    $sel = !empty($opt['selected']);
  } else {
    $val = (string)$opt; $lab = (string)$opt; $sel = false;
  }
  echo '<option value="' . htmlspecialchars($val) . '"' . ($sel ? ' selected' : '') . '>' . htmlspecialchars($lab) . '</option>';
}
echo '</select>';

