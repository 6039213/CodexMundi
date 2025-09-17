<?php
// UI/Modal component
// Params: $id (req), $title (opt), $body (html), $footer (html)
$id = $id ?? 'modal';
$title = $title ?? '';
$body = $body ?? '';
$footer = $footer ?? '';
?>
<div id="<?php echo htmlspecialchars((string)$id) ?>-backdrop" class="modal-backdrop" role="dialog" aria-modal="true" aria-labelledby="<?php echo htmlspecialchars((string)$id) ?>-title">
  <div class="modal">
    <?php if ($title): ?><h3 id="<?php echo htmlspecialchars((string)$id) ?>-title"><?php echo htmlspecialchars((string)$title) ?></h3><?php endif; ?>
    <div class="modal-body"><?php echo $body; ?></div>
    <?php if ($footer): ?><div class="modal-footer"><?php echo $footer; ?></div><?php endif; ?>
  </div>
  
</div>
<script>
window.toggleModal = function(id, open){
  var el = document.getElementById(id+"-backdrop"); if(!el) return;
  if (open===undefined) open = !el.classList.contains('open');
  el.classList.toggle('open', !!open);
};
</script>

