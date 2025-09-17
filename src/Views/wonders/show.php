<div class="card">
  <h2 style="margin-top:0"><?php echo htmlspecialchars($wonder['name']) ?></h2>
  <p class="prose"><?php echo nl2br(htmlspecialchars($wonder['short_description'] ?? '')) ?></p>
  <p>
    <span class="badge"><?php echo htmlspecialchars($wonder['continent'] ?? '-') ?></span>
    <span class="badge"><?php echo htmlspecialchars($wonder['type'] ?? '-') ?></span>
    <span class="badge"><?php echo ((int)$wonder['exists_now'] ? 'Bestaat' : 'Verdwenen') ?></span>
    <?php if (!(int)$wonder['approved']): ?><span class="badge">Wacht op goedkeuring</span><?php endif; ?>
  </p>
  <p><strong>Jaar:</strong> <?php echo htmlspecialchars((string)($wonder['year'] ?? '-')) ?></p>
  <div class="prose">
    <p><strong>Mythe:</strong><br><?php echo nl2br(htmlspecialchars($wonder['myth'] ?? '')) ?></p>
    <p><strong>Verhaal:</strong><br><?php echo nl2br(htmlspecialchars($wonder['story'] ?? '')) ?></p>
  </div>
  <p><strong>Locatie:</strong> <?php echo htmlspecialchars((string)($wonder['lat'] ?? '?')) ?>, <?php echo htmlspecialchars((string)($wonder['lng'] ?? '?')) ?></p>
  <?php if (!empty($tags)): ?>
    <p><strong>Tags:</strong>
      <?php foreach ($tags as $t): ?>
        <span class="badge"><?php echo htmlspecialchars($t) ?></span>
      <?php endforeach; ?>
    </p>
  <?php endif; ?>

  <?php if (!empty($user) && in_array($user['role'], ['onderzoeker','beheerder','archivaris'])): ?>
    <p class="form-actions">
      <a href="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/edit" class="btn">Bewerken</a>
      <?php if ($user['role']==='beheerder'): ?>
        <form action="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/delete" method="post" class="inline" onsubmit="return confirm('Verwijderen?')">
          <button class="btn btn-danger">Verwijderen</button>
        </form>
      <?php endif; ?>
    </p>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Foto</h3>
  <?php
    $firstApproved = $photos[0]['path'] ?? null;
    $fallback = $wonder['cover'] ?: ('/uploads/photos/wonder_' . (int)$wonder['id'] . '.svg');
    $imgSrc = \CodexMundi\Core\Url::resolveMedia((string)($firstApproved ?: $fallback), $base);
  ?>
  <?php if ($imgSrc): ?>
    <a href="<?php echo htmlspecialchars($imgSrc); ?>" target="_blank" rel="noopener noreferrer">
      <img class="cover" src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Foto van <?php echo htmlspecialchars($wonder['name']) ?>">
    </a>
  <?php else: ?>
    <p class="muted">Nog geen foto.</p>
  <?php endif; ?>
  <?php if (!empty($user) && in_array($user['role'], ['onderzoeker','beheerder'])): ?>
    <form method="post" action="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/photos" enctype="multipart/form-data" class="space-y">
      <label>Titel</label><input name="title">
      <label>Foto (jpg, png, webp)</label><input type="file" name="photo" accept="image/*">
      <div class="muted">of voeg toe via URL:</div>
      <input name="photo_url" placeholder="https://…/afbeelding.jpg">
      <button class="btn btn-primary">Uploaden</button>
    </form>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Documenten</h3>
  <ul>
    <?php foreach ($docs as $d): $dpath=(string)$d['path']; $href=(isset($dpath[0]) && $dpath[0]==='/') ? ($base.$dpath) : $dpath; ?>
      <li><a href="<?php echo htmlspecialchars($href); ?>" target="_blank"><?php echo htmlspecialchars($d['title'] ?: basename($d['path'])) ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php if (!empty($user) && in_array($user['role'], ['archivaris','onderzoeker','beheerder'])): ?>
    <form method="post" action="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/documents" enctype="multipart/form-data" class="space-y">
      <label>Titel</label><input name="title">
      <label>Document (pdf, txt)</label><input type="file" name="document" accept="application/pdf,text/plain">
      <div class="muted">of voeg toe via URL:</div>
      <input name="document_url" placeholder="https://…">
      <button class="btn btn-primary">Uploaden</button>
    </form>
  <?php endif; ?>
</div>
