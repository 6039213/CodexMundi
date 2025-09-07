<?php
require_once __DIR__ . '/../../app/lib/helpers.php';
require_once __DIR__ . '/../../app/lib/auth.php';
require_auth();
$user = current_user();
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<main class="container">
	<h1>Dashboard</h1>
	<div class="grid grid-3" style="margin-top:16px">
		<div class="card"><div class="content"><h3>Mijn records</h3><p class="sub">Overzicht van jouw ingestuurde wonders</p></div></div>
		<div class="card"><div class="content"><h3>Te keuren</h3><p class="sub">Wachten op review</p></div></div>
		<div class="card"><div class="content"><h3>Statistiek</h3><p class="sub">Kleine tellingen</p></div></div>
	</div>
	<nav class="chips" style="margin-top:16px">
		<?php if (is_role('researcher')): ?><a class="btn" href="/dashboard/wonders.php">Mijn wonderen</a><?php endif; ?>
		<?php if (is_role('editor') || is_role('admin')): ?><a class="btn" href="/dashboard/review_queue.php">Review queue</a><?php endif; ?>
		<?php if (is_role('archivist') || is_role('admin')): ?><span class="btn" aria-disabled="true">Historiek & GPS</span><?php endif; ?>
		<?php if (is_role('admin')): ?><a class="btn" href="/dashboard/users.php">Users</a><?php endif; ?>
	</nav>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>


