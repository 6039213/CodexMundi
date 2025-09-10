<div class="card">
  <h2>Te beoordelen</h2>
  <h3>Foto's</h3>
  <table>
    <thead><tr><th>Wonder</th><th>Voorbeeld</th><th>Titel</th><th>Acties</th></tr></thead>
    <tbody>
      <?php foreach ($pendingPhotos as $p): ?>
        <tr>
          <td><?php echo htmlspecialchars($p['wonder_name']) ?></td>
          <?php $pp=(string)$p['path']; $psrc=(isset($pp[0]) && $pp[0]==='/')?($base.$pp):$pp; ?>
          <td><img src="<?php echo htmlspecialchars($psrc); ?>" style="max-height:60px"></td>
          <td><?php echo htmlspecialchars($p['title'] ?? '') ?></td>
          <td>
            <form method="post" action="<?php echo $base; ?>/admin/photos/<?php echo (int)$p['id'] ?>/approve" style="display:inline"><button>Goedkeuren</button></form>
            <form method="post" action="<?php echo $base; ?>/admin/photos/<?php echo (int)$p['id'] ?>/reject" style="display:inline"><button style="background:#c62828">Afkeuren</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <h3>Wijzigingen wonderen</h3>
  <table>
    <thead><tr><th>Naam</th><th>Laatst bijgewerkt</th><th>Acties</th></tr></thead>
    <tbody>
      <?php foreach ($pendingWonders as $w): ?>
        <tr>
          <td><a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>"><?php echo htmlspecialchars($w['name']) ?></a></td>
          <td><?php echo htmlspecialchars($w['updated_at']) ?></td>
          <td>
            <form method="post" action="<?php echo $base; ?>/admin/wonders/<?php echo (int)$w['id'] ?>/approve" style="display:inline"><button>Goedkeuren</button></form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
