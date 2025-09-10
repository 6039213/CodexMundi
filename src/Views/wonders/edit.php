<div class="card">
  <h2>Wereldwonder bewerken</h2>
<form method="post" action="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>">
    <label>Naam</label><input name="name" value="<?php echo htmlspecialchars($wonder['name']) ?>">
    <label>Korte beschrijving</label><textarea name="short_description"><?php echo htmlspecialchars($wonder['short_description'] ?? '') ?></textarea>
    <label>Jaar</label><input type="number" name="year" value="<?php echo htmlspecialchars((string)($wonder['year'] ?? '')) ?>">
    <label>Werelddeel</label>
    <select name="continent">
      <?php $continents=['Africa','Europe','Asia','North America','South America','Oceania']; foreach ($continents as $c): ?>
        <option <?php echo ($wonder['continent']===$c?'selected':'') ?>><?php echo $c ?></option>
      <?php endforeach; ?>
    </select>
    <label>Type</label>
    <select name="type"><option value="classic" <?php echo ($wonder['type']==='classic'?'selected':'') ?>>klassiek</option><option value="modern" <?php echo ($wonder['type']==='modern'?'selected':'') ?>>modern</option></select>
    <label><input type="checkbox" name="exists_now" <?php echo ((int)$wonder['exists_now']? 'checked':'') ?>> Bestaat nog</label>
    <label>Mythe</label><textarea name="myth"><?php echo htmlspecialchars($wonder['myth'] ?? '') ?></textarea>
    <label>Verhaal</label><textarea name="story"><?php echo htmlspecialchars($wonder['story'] ?? '') ?></textarea>
    <label>Latitude</label><input type="number" step="any" name="lat" value="<?php echo htmlspecialchars((string)($wonder['lat'] ?? '')) ?>">
    <label>Longitude</label><input type="number" step="any" name="lng" value="<?php echo htmlspecialchars((string)($wonder['lng'] ?? '')) ?>">
    <?php if (!empty($user) && in_array($user['role'], ['redacteur','beheerder'])): ?>
      <label>Tags (komma-gescheiden)</label>
      <input name="tags" value="<?php echo htmlspecialchars(implode(', ', $tags ?? [])) ?>">
    <?php endif; ?>
    <button>Opslaan</button>
  </form>
</div>
