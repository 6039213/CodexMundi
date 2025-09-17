<div class="card">
  <h2 style="margin-top:0">Instellingen uploads</h2>
  <?php
    $maxPhoto = \CodexMundi\Services\Settings::maxPhotoSize();
    $maxDoc = \CodexMundi\Services\Settings::maxDocSize();
    $typesPhoto = implode(',', \CodexMundi\Services\Settings::allowedPhotoTypes());
    $typesDoc = implode(',', \CodexMundi\Services\Settings::allowedDocTypes());
    $loginMax = \CodexMundi\Services\Settings::loginMaxAttemptsPerMinute();
    $pwdMin = \CodexMundi\Services\Settings::passwordMinLength();
  ?>
<form method="post" action="<?php echo $base; ?>/admin/settings" class="space-y">
    <label>Max fotogrootte (bytes)</label>
    <input type="number" name="max_photo_size" value="<?php echo (int)$maxPhoto ?>">
    <label>Max documentgrootte (bytes)</label>
    <input type="number" name="max_doc_size" value="<?php echo (int)$maxDoc ?>">
    <label>Toegestane foto MIME types (comma)</label>
    <input name="allowed_photo_types" value="<?php echo htmlspecialchars($typesPhoto) ?>">
    <label>Toegestane document MIME types (comma)</label>
    <input name="allowed_doc_types" value="<?php echo htmlspecialchars($typesDoc) ?>">
    <hr>
    <h3>Beveiliging inloggen</h3>
    <label>Max. inlogpogingen per minuut</label>
    <input type="number" name="login_max_attempts_per_minute" value="<?php echo (int)$loginMax ?>">
    <label>Min. wachtwoordlengte (registratie)</label>
    <input type="number" name="password_min_length" value="<?php echo (int)$pwdMin ?>">
    <div class="form-actions">
      <button class="btn btn-primary">Opslaan</button>
    </div>
  </form>
</div>
