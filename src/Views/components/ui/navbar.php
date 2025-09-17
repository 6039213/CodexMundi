<?php
// UI/Navbar (simple container)
// Params: $leftHtml, $rightHtml
$leftHtml = $leftHtml ?? '';
$rightHtml = $rightHtml ?? '';
?>
<div class="container flex between center">
  <div><?php echo $leftHtml; ?></div>
  <nav class="nav"><?php echo $rightHtml; ?></nav>
</div>

