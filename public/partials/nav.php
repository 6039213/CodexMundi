
<header class="site-header">
	<div class="container header-inner">
		<a class="logo" href="/">Codex Mundi</a>
		<nav class="main-nav" aria-label="Hoofdnavigatie">
			<a data-nav="/" href="/">Home</a>
			<a data-nav="/wonders.php" href="/wonders.php">Wonders</a>
			<a data-nav="/map.php" href="/map.php">Map</a>
			<?php if (current_user()): ?>
				<a data-nav="/dashboard/index.php" href="/dashboard/index.php">Account</a>
				<a href="/logout.php" class="sub">Logout</a>
			<?php else: ?>
				<a data-nav="/login.php" href="/login.php">Login</a>
				<a data-nav="/register.php" href="/register.php" class="sub">Register</a>
			<?php endif; ?>
		</nav>
	</div>
</header>
