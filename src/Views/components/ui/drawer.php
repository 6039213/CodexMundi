<?php
// UI/Drawer component (right side)
// Params: $id (req), $title (opt), $content (html)
$id = $id ?? 'drawer';
$title = $title ?? '';
$content = $content ?? '';
?>
<div id="<?php echo htmlspecialchars((string)$id) ?>" class="drawer" role="dialog" aria-modal="true" aria-labelledby="<?php echo htmlspecialchars((string)$id) ?>-title">
  <?php if ($title): ?><h3 id="<?php echo htmlspecialchars((string)$id) ?>-title"><?php echo htmlspecialchars((string)$title) ?></h3><?php endif; ?>
  <div class="drawer-body"><?php echo $content; ?></div>
</div>
<script>
window.toggleDrawer = function(id, open){
  var el = document.getElementById(id); if(!el) return;
  if (open===undefined) open = !el.classList.contains('open');
  el.classList.toggle('open', !!open);
};
</script>

