<?php
require_once __DIR__ . '/../app/lib/helpers.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
$error = null; $success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$err = UserController::register();
	if ($err) { $error = $err; } else { $success = true; }
}
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main class="container">
	<h1>Registreren</h1>
	<?php if ($success): ?>
		<div class="alert show">Account aangemaakt. Je kunt nu inloggen.</div>
	<?php endif; ?>
	<?php if ($error): ?>
		<div class="alert show"><?php echo e($error); ?></div>
	<?php endif; ?>
	<div class="card" style="max-width:420px;padding:16px">
		<form method="post">
			<?php echo csrf_field(); ?>
			<label for="email">E-mail</label>
			<input class="input" type="email" name="email" id="email" required>
			<label for="password">Wachtwoord</label>
			<input class="input" type="password" name="password" id="password" minlength="8" required>
			<button class="btn" type="submit" style="margin-top:12px">Registreren</button>
		</form>
	</div>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>


