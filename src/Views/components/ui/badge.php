<?php
// UI/Badge component
// Params: $text (req), $attrs (opt)
$text = $text ?? '';
$attrs = $attrs ?? [];
$classes = 'badge' . (!empty($attrs['class']) ? (' ' . $attrs['class']) : '');
unset($attrs['class']);
$attrStr = '';
foreach ($attrs as $k=>$v) { $attrStr .= ' ' . htmlspecialchars((string)$k) . '="' . htmlspecialchars((string)$v) . '"'; }
echo '<span class="' . htmlspecialchars($classes) . '"' . $attrStr . '>' . htmlspecialchars((string)$text) . '</span>';

