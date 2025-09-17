<?php
// UI/Tooltip wrapper
// Params: $content (inner html/text), $tip (string)
$content = $content ?? '';
$tip = $tip ?? '';
echo '<span class="tooltip" data-tip="' . htmlspecialchars((string)$tip) . '">' . $content . '</span>';

