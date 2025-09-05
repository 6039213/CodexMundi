<?php
require_once __DIR__ . '/../../app/lib/helpers.php';
require_once __DIR__ . '/../../app/lib/auth.php';
require_role(['editor','admin']);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<main id="main" class="container">
	<h1>Review queue</h1>
	<table class="table" aria-label="Te keuren">
		<thead><tr><th>Titel</th><th>Status</th><th>Acties</th></tr></thead>
		<tbody>
			<tr><td>Voorbeeld wonder</td><td><span class="badge">PENDING</span></td><td><button class="btn">Approve</button> <button class="btn">Reject</button></td></tr>
		</tbody>
	</table>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>


