<?php $template = $template ?? 'wonders/index.php'; ?>
<!doctype html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Codex Mundi</title>
  <link rel="stylesheet" href="<?php echo $base; ?>/assets/app.css">
  <link rel="icon" href="data:,">
  <meta name="color-scheme" content="light dark">
  <script>
    // Simple prefers-color-scheme toggle (persists in localStorage)
    (function(){
      try{
        const pref = localStorage.getItem('theme');
        if(pref){ document.documentElement.dataset.theme = pref; }
      }catch(e){}
    })();
  </script>
  </head>
<body>
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
    <nav class="nav">
      <a class="nav-item <?php echo $isActive('/'); ?>" href="<?php echo $base; ?>/">Overzicht</a>
      <a class="nav-item <?php echo $isActive('/search'); ?>" href="<?php echo $base; ?>/search">Zoeken</a>
      <a class="nav-item <?php echo $isActive('/map'); ?>" href="<?php echo $base; ?>/map">Kaart</a>
      <?php if (!empty($user)): ?>
        <?php if (in_array($user['role'], ['onderzoeker','beheerder'])): ?>
          <a class="nav-item <?php echo $isActive('/wonders/create'); ?>" href="<?php echo $base; ?>/wonders/create">Nieuw wonder</a>
          <a class="nav-item <?php echo $isActive('/my'); ?>" href="<?php echo $base; ?>/my">Mijn bijdragen</a>
        <?php endif; ?>
        <?php if (in_array($user['role'], ['redacteur','beheerder'])): ?>
          <a class="nav-item <?php echo $isActive('/admin'); ?>" href="<?php echo $base; ?>/admin">Beoordelingen<?php if (!empty($pending_total)) echo ' <span class=\'badge\'>' . (int)$pending_total . '</span>'; ?></a>
          <a class="nav-item <?php echo $isActive('/stats'); ?>" href="<?php echo $base; ?>/stats">Statistieken</a>
          <?php if ($user['role']==='beheerder'): ?>
            <a href="<?php echo $base; ?>/admin/logs">Logs</a>
          <?php endif; ?>
        <?php endif; ?>
        <form action="<?php echo $base; ?>/logout" method="post" class="inline"><button class="btn btn-secondary">Uitloggen (<?php echo htmlspecialchars($user['name']) ?><?php if (!empty($user['role'])) echo ' · ' . htmlspecialchars($user['role']); ?>)</button></form>
      <?php else: ?>
        <a class="nav-item <?php echo $isActive('/login'); ?>" href="<?php echo $base; ?>/login">Inloggen</a>
        <a href="<?php echo $base; ?>/register" class="btn btn-primary">Registreren</a>
      <?php endif; ?>
    </nav>
  </div>
  <?php if (!empty($flash)): ?>
    <div class="container"><div class="alert <?php echo htmlspecialchars($flash['type']) ?>"><?php echo htmlspecialchars($flash['message']) ?></div></div>
  <?php endif; ?>
</header>

<main class="container space-y">
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
</body>
</html>
