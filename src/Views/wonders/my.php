<div class="card">
  <h2>Mijn bijdragen</h2>
  <?php if (empty($mine)): ?>
    <p class="muted">Nog geen inzendingen. <a href="<?php echo $base; ?>/wonders/create">Voeg je eerste wonder toe</a>.</p>
  <?php else: ?>
    <table>
      <thead><tr><th>Naam</th><th>Status</th><th>Laatst bewerkt</th><th>Acties</th></tr></thead>
      <tbody>
        <?php foreach ($mine as $w): ?>
          <tr>
            <td><a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>"><?php echo htmlspecialchars($w['name']) ?></a></td>
            <td><?php echo ((int)$w['approved'] ? '<span class="badge">Goedgekeurd</span>' : '<span class="badge">In beoordeling</span>'); ?></td>
            <td><?php echo htmlspecialchars($w['updated_at'] ?? '') ?></td>
            <td><a class="btn" href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>/edit">Bewerken</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>

