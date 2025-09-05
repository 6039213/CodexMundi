<?php
require_once __DIR__ . '/../../app/lib/helpers.php';
require_once __DIR__ . '/../../app/lib/auth.php';
require_once __DIR__ . '/../../app/models/Wonder.php';
require_role(['researcher','admin']);
$user = current_user();
$data = Wonder::list(['status'=>null,'limit'=>20]);
$items = $data['items'];
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<main id="main" class="container">
	<h1>Mijn wonderen</h1>
        <a class="btn" href="/dashboard/wonder_new.php">Nieuw wonder</a>
	<table class="table" aria-label="Mijn records" style="margin-top:12px">
		<thead><tr><th>Titel</th><th>Status</th><th>Continent</th><th>Categorie</th><th>Acties</th></tr></thead>
		<tbody>
			<?php foreach($items as $w): ?>
			<tr>
				<td><?php echo e($w['title']); ?></td>
				<td>
					<span class="badge" style="background:<?php echo $w['status']==='draft'?'#2a2e36':($w['status']==='pending'?'#6ea8fe':'#12351e'); ?>;border-color:rgba(255,255,255,.1)">
						<?php echo strtoupper($w['status']); ?>
					</span>
				</td>
				<td><?php echo e($w['continent']); ?></td>
				<td><?php echo e($w['category']); ?></td>
				<td>
                                        <a class="btn" href="/dashboard/wonder_edit.php?id=<?php echo (int)$w['id']; ?>">Bewerken</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>


