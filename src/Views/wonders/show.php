<div class="card">
  <h2><?php echo htmlspecialchars($wonder['name']) ?></h2>
  <p><?php echo nl2br(htmlspecialchars($wonder['short_description'] ?? '')) ?></p>
  <p>
    <span class="badge"><?php echo htmlspecialchars($wonder['continent'] ?? '-') ?></span>
    <span class="badge"><?php echo htmlspecialchars($wonder['type'] ?? '-') ?></span>
    <span class="badge"><?php echo ((int)$wonder['exists_now'] ? 'Bestaat' : 'Verdwenen') ?></span>
    <?php if (!(int)$wonder['approved']): ?><span class="badge">Wacht op goedkeuring</span><?php endif; ?>
  </p>
  <p><strong>Jaar:</strong> <?php echo htmlspecialchars((string)($wonder['year'] ?? '-')) ?></p>
  <p><strong>Mythe:</strong><br><?php echo nl2br(htmlspecialchars($wonder['myth'] ?? '')) ?></p>
  <p><strong>Verhaal:</strong><br><?php echo nl2br(htmlspecialchars($wonder['story'] ?? '')) ?></p>
  <p><strong>Locatie:</strong> <?php echo htmlspecialchars((string)($wonder['lat'] ?? '?')) ?>, <?php echo htmlspecialchars((string)($wonder['lng'] ?? '?')) ?></p>
  <?php if (!empty($tags)): ?>
    <p><strong>Tags:</strong>
      <?php foreach ($tags as $t): ?>
        <span class="badge"><?php echo htmlspecialchars($t) ?></span>
      <?php endforeach; ?>
    </p>
  <?php endif; ?>

  <?php if (!empty($user) && in_array($user['role'], ['onderzoeker','beheerder','archivaris'])): ?>
    <p>
      <a href="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/edit"><button>Bewerken</button></a>
      <?php if ($user['role']==='beheerder'): ?>
        <form action="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/delete" method="post" style="display:inline" onsubmit="return confirm('Verwijderen?')">
          <button style="background:#c62828">Verwijderen</button>
        </form>
      <?php endif; ?>
    </p>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Foto's</h3>
  <?php $slides = array_map(function($p) use ($base){ $path=(string)$p['path']; $src=\CodexMundi\Core\Url::resolveMedia($path, $base); return ['src'=>$src,'title'=>$p['title']??'']; }, $photos); ?>
  <?php if (!empty($slides)): $first=$slides[0]; ?>
    <div class="slider" data-index="0">
      <div class="slider-main">
        <a id="slide-link" href="<?php echo htmlspecialchars($first['src']); ?>" target="_blank" rel="noopener noreferrer">
          <img id="slide-main" src="<?php echo htmlspecialchars($first['src']); ?>" alt="">
        </a>
        <button class="slider-btn prev" type="button" onclick="slidePrev(this)">&#9664;</button>
        <button class="slider-btn next" type="button" onclick="slideNext(this)">&#9654;</button>
      </div>
      <div class="slider-nav">
        <?php foreach ($slides as $i=>$s): ?>
          <a href="<?php echo htmlspecialchars($s['src']); ?>" target="_blank" rel="noopener noreferrer">
            <img class="slider-thumb <?php echo $i===0?'active':''; ?>" src="<?php echo htmlspecialchars($s['src']); ?>" alt="" onclick="slideGo(this, <?php echo $i; ?>)">
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <script>
    function findSlider(el){ while(el && !el.classList.contains('slider')) el=el.parentElement; return el; }
    function slideSet(slider, idx){
      var thumbs = slider.querySelectorAll('.slider-thumb');
      if (!thumbs.length) return;
      idx = (idx+thumbs.length)%thumbs.length;
      slider.dataset.index = idx;
      var main = slider.querySelector('#slide-main');
      var link = slider.querySelector('#slide-link');
      main.src = thumbs[idx].src;
      link.href = thumbs[idx].src;
      thumbs.forEach(function(t,k){ t.classList.toggle('active', k===idx); });
    }
    function slidePrev(btn){ var s=findSlider(btn); slideSet(s, (parseInt(s.dataset.index||'0',10)-1)); }
    function slideNext(btn){ var s=findSlider(btn); slideSet(s, (parseInt(s.dataset.index||'0',10)+1)); }
    function slideGo(thumb, idx){ var s=findSlider(thumb); slideSet(s, idx); }
    </script>
  <?php else: ?>
    <p class="muted">Nog geen foto's.</p>
  <?php endif; ?>
  <?php if (!empty($user) && in_array($user['role'], ['onderzoeker','beheerder'])): ?>
    <form method="post" action="<?php echo $base; ?>/wonders/<?php echo (int)$wonder['id'] ?>/photos" enctype="multipart/form-data" class="space-y">
      <label>Titel</label><input name="title">
      <label>Foto (jpg, png, webp)</label><input type="file" name="photo" accept="image/*">
      <div class="muted">of voeg toe via URL:</div>
      <input name="photo_url" placeholder="https://…/afbeelding.jpg">
      <button>Uploaden</button>
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
      <button>Uploaden</button>
    </form>
  <?php endif; ?>
</div>

