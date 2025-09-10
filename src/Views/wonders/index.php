<div class="card">
  <h2>Wereldwonderen</h2>
  <p>Overzicht van alle wonderen. Alleen goedgekeurde items zijn als zodanig gemarkeerd.</p>
  <div class="grid">
    <?php foreach ($wonders as $w): ?>
      <div class="card">
        <?php $cover = $w['cover'] ?: ('/uploads/photos/wonder_' . (int)$w['id'] . '.svg'); $coverSrc = \CodexMundi\Core\Url::resolveMedia((string)$cover, $base); ?>
        <a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>" class="block">
          <img class="cover" src="<?php echo htmlspecialchars($coverSrc); ?>" alt="Cover: <?php echo htmlspecialchars($w['name']) ?>">
        </a>
        <h3 style="margin:.6rem 0 .2rem"><a href="<?php echo $base; ?>/wonders/<?php echo (int)$w['id'] ?>"><?php echo htmlspecialchars($w['name']) ?></a></h3>
        <p><?php echo htmlspecialchars($w['short_description'] ?? '') ?></p>
        <p>
          <span class="badge"><?php echo htmlspecialchars($w['continent'] ?? '-') ?></span>
          <span class="badge"><?php echo htmlspecialchars($w['type'] ?? '-') ?></span>
          <span class="badge"><?php echo ((int)$w['exists_now'] ? 'Bestaat' : 'Verdwenen') ?></span>
          <?php if (!(int)$w['approved']): ?><span class="badge">Wacht op goedkeuring</span><?php endif; ?>
        </p>
      </div>
    <?php endforeach; ?>
  </div>
</div>
