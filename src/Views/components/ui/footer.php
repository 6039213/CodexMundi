<?php
// UI/Footer (simple wrapper)
// Params: $leftHtml, $rightHtml
$leftHtml = $leftHtml ?? '';
$rightHtml = $rightHtml ?? '';
?>
<div class="container flex between center">
  <small><?php echo $leftHtml; ?></small>
  <div class="muted"><?php echo $rightHtml; ?></div>
</div>

