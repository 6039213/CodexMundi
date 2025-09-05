<?php
require_once __DIR__ . '/../../app/lib/helpers.php';
require_once __DIR__ . '/../../app/lib/auth.php';
require_once __DIR__ . '/../../app/models/User.php';
require_once __DIR__ . '/../../app/controllers/UserController.php';
require_role(['admin']);
$users = User::all();
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<main class="container">
	<h1>Users</h1>
	<table class="table" aria-label="Users">
		<thead><tr><th>ID</th><th>Email</th><th>Rol</th><th>Aangemaakt</th><th>Actie</th></tr></thead>
		<tbody>
			<?php foreach($users as $u): ?>
			<tr>
				<td><?php echo (int)$u['id']; ?></td>
				<td><?php echo e($u['email']); ?></td>
				<td>
					<form method="post" action="/app/controllers/user_role_update.php">
						<?php echo csrf_field(); ?>
						<input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
						<select name="role" class="input">
							<?php foreach(['visitor','researcher','editor','archivist','admin'] as $r): ?>
								<option value="<?php echo $r; ?>" <?php echo $u['role']===$r?'selected':''; ?>><?php echo $r; ?></option>
							<?php endforeach; ?>
						</select>
						<button class="btn" type="submit">Opslaan</button>
					</form>
				</td>
				<td><?php echo e($u['created_at']); ?></td>
				<td><button class="btn" aria-disabled="true">Export</button></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>


