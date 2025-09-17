<?php
// UI/Card wrapper
// Params: $title (opt), $content (html)
$title = $title ?? '';
$content = $content ?? '';
?>
<div class="card">
  <?php if ($title): ?><h3><?php echo htmlspecialchars((string)$title) ?></h3><?php endif; ?>
  <?php echo $content; ?>
</div>

