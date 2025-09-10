<div class="card">
  <h2>Zoeken en filteren</h2>
  <form method="get" action="<?php echo $base; ?>/search" class="grid">
    <div>
      <label>Naam</label>
      <input name="q" value="<?php echo htmlspecialchars($q ?? '') ?>">
    </div>
    <div>
      <label>Werelddeel</label>
      <select name="continent">
        <option value="">- alle -</option>
        <?php foreach(['Africa','Europe','Asia','North America','South America','Oceania'] as $c): ?>
          <option value="<?php echo $c ?>" <?php echo (($continent??'')===$c?'selected':'') ?>><?php echo $c ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Type</label>
      <select name="type">
        <option value="">- alle -</option>
        <option value="classic" <?php echo (($type??'')==='classic'?'selected':'') ?>>klassiek</option>
        <option value="modern" <?php echo (($type??'')==='modern'?'selected':'') ?>>modern</option>
      </select>
    </div>
    <div>
      <label>Sorteer</label>
      <select name="sort">
        <option value="name" <?php echo (($sort??'')==='name'?'selected':'') ?>>alfabet</option>
        <option value="year" <?php echo (($sort??'')==='year'?'selected':'') ?>>jaartal</option>
      </select>
    </div>
    <div style="align-self:end"><button>Zoeken</button></div>
  </form>
</div>

<div class="card">
  <h3>Resultaten</h3>
  <table>
    <thead><tr><th>Naam</th><th>Continent</th><th>Type</th><th>Jaar</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach ($wonders as $w): ?>
        <tr>
          <td><a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>"><?php echo htmlspecialchars($w['name']) ?></a></td>
          <td><?php echo htmlspecialchars($w['continent'] ?? '-') ?></td>
          <td><?php echo htmlspecialchars($w['type'] ?? '-') ?></td>
          <td><?php echo htmlspecialchars((string)($w['year'] ?? '-')) ?></td>
          <td><?php echo ((int)$w['exists_now'] ? 'Bestaat' : 'Verdwenen') ?> <?php if (!(int)$w['approved']): ?><span class="badge">(wacht op goedkeuring)</span><?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
