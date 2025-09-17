<?php
// UI/Tabs (ARIA-ready skeleton)
// Params: $tabs = [ ['id'=>'tab1','label'=>'Tab 1','selected'=>true], ... ]
$tabs = $tabs ?? [];
?>
<div class="tabs" role="tablist">
  <?php foreach ($tabs as $t): $id=(string)($t['id'] ?? 'tab'); $sel=!empty($t['selected']); ?>
    <button class="tab" role="tab" id="<?php echo htmlspecialchars($id) ?>" aria-selected="<?php echo $sel?'true':'false' ?>"><?php echo htmlspecialchars((string)($t['label'] ?? $id)) ?></button>
  <?php endforeach; ?>
</div>

