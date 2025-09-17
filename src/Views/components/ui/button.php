<?php
// UI/Button component
// Usage:
//   $label (string, required)
//   $variant ('primary'|'secondary'|'link'|'default')
//   $type ('button'|'submit'|'reset')
//   $href (string|null) if set, renders <a>
//   $attrs (array) extra attributes
$label = $label ?? '';
$variant = $variant ?? 'default';
$type = $type ?? 'button';
$attrs = $attrs ?? [];
$classes = 'btn';
if ($variant === 'primary') $classes .= ' btn-primary';
elseif ($variant === 'secondary') $classes .= ' btn-secondary';
elseif ($variant === 'link') $classes = 'btn-link';

// merge class attr
if (!empty($attrs['class'])) { $classes .= ' ' . $attrs['class']; unset($attrs['class']); }

// render attributes safely
$attrStr = '';
foreach ($attrs as $k=>$v) { $attrStr .= ' ' . htmlspecialchars((string)$k) . '="' . htmlspecialchars((string)$v) . '"'; }

if (!empty($href)) {
  echo '<a href="' . htmlspecialchars((string)$href) . '" class="' . htmlspecialchars($classes) . '"' . $attrStr . '>' . htmlspecialchars((string)$label) . '</a>';
} else {
  echo '<button type="' . htmlspecialchars((string)$type) . '" class="' . htmlspecialchars($classes) . '"' . $attrStr . '>' . htmlspecialchars((string)$label) . '</button>';
}

