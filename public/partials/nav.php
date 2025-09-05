
<header class="site-header">
	<div class="container header-inner">
		<a class="logo" href="/public/index.php">Codex Mundi</a>
		<nav class="main-nav" aria-label="Hoofdnavigatie">
			<a data-nav="/public/index.php" href="/public/index.php">Home</a>
			<a data-nav="/public/wonders.php" href="/public/wonders.php">Wonders</a>
			<a data-nav="/public/map.php" href="/public/map.php">Map</a>
			<?php if (current_user()): ?>
				<a data-nav="/public/dashboard/index.php" href="/public/dashboard/index.php">Account</a>
				<a href="/public/logout.php" class="sub">Logout</a>
			<?php else: ?>
				<a data-nav="/public/login.php" href="/public/login.php">Login</a>
				<a data-nav="/public/register.php" href="/public/register.php" class="sub">Register</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
