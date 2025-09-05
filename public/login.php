<?php
require_once __DIR__ . '/../app/lib/helpers.php';
require_once __DIR__ . '/../app/controllers/UserController.php';
$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$error = UserController::login();
}
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main id="main" class="container">
	<h1>Login</h1>
	<div class="alert <?php echo $error? 'show':''; ?>" id="toast" role="status"><?php echo e($error ?: ''); ?></div>
	<div class="card" style="max-width:420px;padding:16px">
		<form method="post">
			<?php echo csrf_field(); ?>
			<label for="email">E-mail</label>
			<input class="input" type="email" name="email" id="email" required>
			<label for="password">Wachtwoord</label>
			<input class="input" type="password" name="password" id="password" required>
			<button class="btn accent" type="submit" style="margin-top:12px">Inloggen</button>
		</form>
	</div>
	<p class="sub" style="margin-top:16px">Demo-accounts:
		<br>admin@demo.test / Admin123!
		<br>researcher@demo.test / Research123!
		<br>editor@demo.test / Editor123!
	</p>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>


