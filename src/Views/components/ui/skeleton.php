<?php
// UI/Skeleton block
// Params: $width (css), $height (css), $style (extra)
$width = $width ?? '100%';
$height = $height ?? '1rem';
$style = $style ?? '';
echo '<div class="skeleton" style="width:' . htmlspecialchars((string)$width) . ';height:' . htmlspecialchars((string)$height) . ';' . htmlspecialchars((string)$style) . '"></div>';

