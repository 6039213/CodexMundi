<?php
// UI/Accordion (simple disclosure items)
// Params: $items = [ ['summary'=>'Title','content'=>'<p>...</p>','open'=>false], ... ]
$items = $items ?? [];
?>
<div class="accordion">
  <?php foreach ($items as $i=>$it): $open=!empty($it['open']); ?>
    <div class="accordion-item<?php echo $open?' open':''; ?>">
      <div class="accordion-summary" onclick="this.parentElement.classList.toggle('open')" role="button" aria-expanded="<?php echo $open?'true':'false'; ?>"><?php echo htmlspecialchars((string)($it['summary'] ?? '')) ?></div>
      <div class="accordion-content"><?php echo (string)($it['content'] ?? '') ?></div>
    </div>
  <?php endforeach; ?>
</div>

