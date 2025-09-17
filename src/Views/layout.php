<?php $template = $template ?? 'wonders/index.php'; ?>
<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo isset($title) && $title ? htmlspecialchars($title) . ' — ' : ''; ?>Codex Mundi</title>
  <link rel="stylesheet" href="<?php echo $base; ?>/assets/app.css">
  <link rel="stylesheet" href="<?php echo $base; ?>/assets/app.premium.css">
  <link rel="icon" href="data:,">
  <meta name="color-scheme" content="light dark">
  <script>
    // Simple prefers-color-scheme toggle (persists in localStorage)
    (function(){
      try{
        const pref = localStorage.getItem('theme');
        if(pref){ document.documentElement.dataset.theme = pref; }
        else { document.documentElement.dataset.theme = 'light'; }
      }catch(e){}
    })();
  </script>
  </head>
<body>
<a class="skip-link" href="#main">Ga naar inhoud</a>
<header class="site-header">
  <?php
    $req = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
    if ($scriptDir && $scriptDir !== '/' && strpos($req, $scriptDir) === 0) {
        $req = substr($req, strlen($scriptDir)) ?: '/';
    }
    $isActive = function(string $path) use ($req): string { return $req === $path ? 'active' : ''; };
  ?>
  <div class="container flex between center">
    <a class="brand" href="<?php echo $base; ?>/">
      <img src="<?php echo $base; ?>/assets/logo.svg" alt="Codex Mundi" class="brand-logo">
    </a>
    <button class="nav-toggle" aria-expanded="false" aria-label="Menu" onclick="(function(btn){var n=btn.nextElementSibling;n.classList.toggle('open');var open=n.classList.contains('open');btn.setAttribute('aria-expanded', open?'true':'false')})(this)">☰</button>
    <nav class="nav">
      <a class="nav-item <?php echo $isActive('/'); ?>" <?php echo $isActive('/')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/">Overzicht</a>
      <a class="nav-item <?php echo $isActive('/search'); ?>" <?php echo $isActive('/search')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/search">Zoeken</a>
      <a class="nav-item <?php echo $isActive('/map'); ?>" <?php echo $isActive('/map')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/map">Kaart</a>
      <?php if (!empty($user)): ?>
        <?php if (in_array($user['role'], ['onderzoeker','beheerder'])): ?>
          <a class="nav-item <?php echo $isActive('/wonders/create'); ?>" <?php echo $isActive('/wonders/create')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/wonders/create">Nieuw wonder</a>
          <a class="nav-item <?php echo $isActive('/my'); ?>" <?php echo $isActive('/my')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/my">Mijn bijdragen</a>
        <?php endif; ?>
        <?php if (in_array($user['role'], ['redacteur','beheerder'])): ?>
          <a class="nav-item <?php echo $isActive('/admin'); ?>" <?php echo $isActive('/admin')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/admin">Beoordelingen<?php if (!empty($pending_total)) echo ' <span class=\'badge\'>' . (int)$pending_total . '</span>'; ?></a>
          <a class="nav-item <?php echo $isActive('/stats'); ?>" <?php echo $isActive('/stats')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/stats">Statistieken</a>
          <?php if ($user['role']==='beheerder'): ?>
            <a href="<?php echo $base; ?>/admin/logs">Logs</a>
          <?php endif; ?>
        <?php endif; ?>
        <form action="<?php echo $base; ?>/logout" method="post" class="inline"><button class="btn btn-secondary">Uitloggen (<?php echo htmlspecialchars($user['name']) ?><?php if (!empty($user['role'])) echo ' · ' . htmlspecialchars($user['role']); ?>)</button></form>
      <?php else: ?>
        <a class="nav-item <?php echo $isActive('/login'); ?>" <?php echo $isActive('/login')? 'aria-current="page"':''; ?> href="<?php echo $base; ?>/login">Inloggen</a>
        <a href="<?php echo $base; ?>/register" class="btn btn-primary">Registreren</a>
      <?php endif; ?>
    </nav>
  </div>
  <?php if (strpos($req, '/admin') === 0): ?>
    <div class="container">
      <nav class="admin-nav">
        <a class="admin-nav-item <?php echo $isActive('/admin'); ?>" href="<?php echo $base; ?>/admin">Dashboard</a>
        <a class="admin-nav-item <?php echo $isActive('/stats'); ?>" href="<?php echo $base; ?>/stats">Statistieken</a>
        <a class="admin-nav-item <?php echo $isActive('/admin/users'); ?>" href="<?php echo $base; ?>/admin/users">Gebruikers</a>
        <a class="admin-nav-item <?php echo $isActive('/admin/settings'); ?>" href="<?php echo $base; ?>/admin/settings">Instellingen</a>
        <?php if (!empty($user) && $user['role']==='beheerder'): ?>
          <a class="admin-nav-item <?php echo $isActive('/admin/logs'); ?>" href="<?php echo $base; ?>/admin/logs">Logs</a>
        <?php endif; ?>
      </nav>
    </div>
  <?php endif; ?>
  <?php if (!empty($flash)): ?>
    <div class="container"><div class="alert <?php echo htmlspecialchars($flash['type']) ?>"><?php echo htmlspecialchars($flash['message']) ?></div></div>
  <?php endif; ?>
</header>

<main id="main" class="container space-y">
  <?php include __DIR__ . '/' . $template; ?>
  <div class="to-top"><a href="#">↑ Naar boven</a></div>
  </main>

<footer class="site-footer">
  <div class="container flex between center">
    <small>&copy; <?php echo date('Y'); ?> Codex Mundi</small>
    <div class="muted">
      <?php if (!empty($user) && $user['role']==='beheerder'): ?>
        <a href="<?php echo $base; ?>/export/wonders.csv">Exporteer CSV</a>
      <?php endif; ?>
      <button class="btn-link" onclick="(function(){var t=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=t;try{localStorage.setItem('theme',t)}catch(e){}})()">Thema wisselen</button>
    </div>
  </div>
</footer>
<div id="toast-root" class="toast-root" aria-live="polite" aria-atomic="true"></div>
<!-- Page Loader -->
<div id="page-loader" class="page-loader" aria-hidden="true">
  <div class="loader-card" role="alert" aria-live="assertive">
    <img class="loader-logo" src="<?php echo $base; ?>/assets/logo.svg" alt="Codex Mundi"/>
    <div class="loader-bar" aria-label="Laden" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
      <div class="loader-bar-fill" style="width:0%"></div>
    </div>
    <div class="loader-text muted">Bezig met laden…</div>
  </div>
  <div class="loader-backdrop"></div>
  </div>
<script>
  // Lightweight toast helper (optional usage)
  window.toast = function(msg, type){
    try{
      var root = document.getElementById('toast-root'); if(!root) return;
      var el = document.createElement('div');
      el.className = 'toast' + (type?(' '+type):'');
      el.textContent = String(msg||'');
      root.appendChild(el);
      setTimeout(function(){ el.style.opacity='0'; setTimeout(function(){ el.remove(); }, 250); }, 3000);
    }catch(e){}
  };
  // Render unread notifications as toasts
  (function(){
    try{
      var notifs = <?php echo json_encode($notifications ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
      if (Array.isArray(notifs) && notifs.length){
        notifs.forEach(function(n){ window.toast(n.message, n.type); });
        // mark read in background
        fetch('<?php echo $base; ?>/notifications/read', { method: 'POST' }).catch(function(){});
      }
    }catch(e){}
  })();
  // Page loader with progress bar
  (function(){
    var root = document.getElementById('page-loader');
    var fill = root ? root.querySelector('.loader-bar-fill') : null;
    var bar = root ? root.querySelector('.loader-bar') : null;
    var timer = null, prog = 0, running = false;

    function tick(){
      if (!running) return;
      // Ease towards 90% while active
      var target = 90;
      var delta = Math.max(0.5, (target - prog) * 0.07);
      prog = Math.min(target, prog + delta);
      if (fill) fill.style.width = prog.toFixed(1) + '%';
      if (bar) bar.setAttribute('aria-valuenow', Math.round(prog));
      timer = requestAnimationFrame(tick);
    }
    function start(){
      if (!root || running) return;
      running = true; prog = 0; if (fill) fill.style.width = '0%'; if (bar) bar.setAttribute('aria-valuenow','0');
      root.classList.add('visible');
      timer = requestAnimationFrame(tick);
    }
    function done(){
      if (!root) return;
      // Finish to 100% then hide
      prog = 100; if (fill) fill.style.width = '100%'; if (bar) bar.setAttribute('aria-valuenow','100');
      setTimeout(function(){
        root.classList.remove('visible');
        running = false; cancelAnimationFrame(timer);
      }, 180);
    }
    window.showLoader = start; window.hideLoader = done;
    // Backward compat
    window.showLoading = start; window.hideLoading = done;

    // Only use beforeunload to show the loader for full page navigations

    // Also show on beforeunload for direct navigations
    window.addEventListener('beforeunload', function(){ start(); });
    // Hide if page was restored from bfcache
    window.addEventListener('pageshow', function(e){ if (e.persisted) { done(); } });
    // Ensure hidden on initial load
    if (document.readyState === 'complete' || document.readyState === 'interactive') { setTimeout(done, 0); }
    else { window.addEventListener('DOMContentLoaded', done); }

    // Do not hook fetch or clicks; loader is reserved for full page navigations
  })();

  // Reveal-on-scroll animations
  (function(){
    try{
      var els = Array.prototype.slice.call(document.querySelectorAll('.reveal'));
      if (!('IntersectionObserver' in window)) { els.forEach(function(e){ e.classList.add('in'); }); return; }
      var io = new IntersectionObserver(function(entries){
        entries.forEach(function(en){ if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
      }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
      els.forEach(function(e){ io.observe(e); });
    }catch(e){}
  })();
</script>
</body>
</html>
