<div class="card">
  <h2>Statistieken</h2>
  <h3>Wonderen per continent</h3>
  <table>
    <thead><tr><th>Continent</th><th>Aantal</th></tr></thead>
    <tbody>
      <?php foreach ($perContinent as $c): ?>
        <tr><td><?php echo htmlspecialchars($c['continent'] ?? '-') ?></td><td><?php echo (int)$c['c'] ?></td></tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3>Laatst bewerkt</h3>
  <ul>
    <?php foreach ($latestEdited as $w): ?>
      <li><a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>"><?php echo htmlspecialchars($w['name']) ?></a> (<?php echo htmlspecialchars($w['updated_at']) ?>)</li>
    <?php endforeach; ?>
  </ul>

  <h3>Meest bekeken</h3>
  <ul>
    <?php foreach ($mostViewed as $w): ?>
      <li><a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>"><?php echo htmlspecialchars($w['name']) ?></a> (<?php echo (int)$w['view_count'] ?> views)</li>
    <?php endforeach; ?>
  </ul>
</div>
