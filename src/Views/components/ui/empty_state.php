<?php
// UI/Empty State
// Params: $title, $message, $actionHtml
$title = $title ?? 'Niets gevonden';
$message = $message ?? 'Er is nog niets om te tonen.';
$actionHtml = $actionHtml ?? '';
?>
<div class="empty">
  <div style="font-size:32px;opacity:.7">🗂️</div>
  <h3 style="margin:.25rem 0"><?php echo htmlspecialchars((string)$title) ?></h3>
  <p class="muted" style="margin:.25rem 0 0"><?php echo htmlspecialchars((string)$message) ?></p>
  <?php if ($actionHtml): ?><div style="margin-top:.75rem"><?php echo $actionHtml; ?></div><?php endif; ?>
</div>

