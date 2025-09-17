<?php
// UI/Table helper (keeps markup minimal)
// Params: $headers (array of strings), $rows (array of arrays with cell html/text)
$headers = $headers ?? [];
$rows = $rows ?? [];
?>
<div class="table-wrap">
  <table>
    <?php if ($headers): ?>
      <thead><tr><?php foreach ($headers as $h): ?><th><?php echo htmlspecialchars((string)$h) ?></th><?php endforeach; ?></tr></thead>
    <?php endif; ?>
    <tbody>
      <?php foreach ($rows as $r): ?><tr><?php foreach ($r as $cell): ?><td><?php echo (string)$cell ?></td><?php endforeach; ?></tr><?php endforeach; ?>
    </tbody>
  </table>
</div>

