<div class="card">
  <h2>Instellingen uploads</h2>
  <?php
    $maxPhoto = \CodexMundi\Services\Settings::maxPhotoSize();
    $maxDoc = \CodexMundi\Services\Settings::maxDocSize();
    $typesPhoto = implode(',', \CodexMundi\Services\Settings::allowedPhotoTypes());
    $typesDoc = implode(',', \CodexMundi\Services\Settings::allowedDocTypes());
  ?>
<form method="post" action="<?php echo $base; ?>/admin/settings">
    <label>Max fotogrootte (bytes)</label>
    <input type="number" name="max_photo_size" value="<?php echo (int)$maxPhoto ?>">
    <label>Max documentgrootte (bytes)</label>
    <input type="number" name="max_doc_size" value="<?php echo (int)$maxDoc ?>">
    <label>Toegestane foto MIME types (comma)</label>
    <input name="allowed_photo_types" value="<?php echo htmlspecialchars($typesPhoto) ?>">
    <label>Toegestane document MIME types (comma)</label>
    <input name="allowed_doc_types" value="<?php echo htmlspecialchars($typesDoc) ?>">
    <button>Opslaan</button>
  </form>
</div>
