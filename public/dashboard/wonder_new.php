<?php
require_once __DIR__ . '/../../app/lib/helpers.php';
require_once __DIR__ . '/../../app/lib/auth.php';
require_role(['researcher','admin']);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<main class="container">
	<h1>Nieuw wonder</h1>
	<div class="form-tabs">
		<button class="tab-btn active" data-target="tab-basic">Basis</button>
		<button class="tab-btn" data-target="tab-history">Historie</button>
		<button class="tab-btn" data-target="tab-media">Media</button>
		<button class="tab-btn" data-target="tab-location">Locatie</button>
		<button class="tab-btn" data-target="tab-tags">Tags</button>
	</div>
	<section id="tab-basic" class="tab-panel active">
		<form method="post">
			<?php echo csrf_field(); ?>
			<label>Titel</label><input class="input" />
			<label>Slug</label><input class="input" />
			<label>Land</label><input class="input" />
			<label>Continent</label>
			<select class="input"><option>asia</option><option>europe</option></select>
			<label>Categorie</label>
			<select class="input"><option>classic</option><option>modern</option><option>natural</option></select>
			<button class="btn" type="submit">Opslaan (DRAFT)</button>
		</form>
	</section>
	<section id="tab-history" class="tab-panel">
		<label>Jaar gebouwd</label><input class="input" type="number" />
		<label>Bestaat nog</label><select class="input"><option value="1">Ja</option><option value="0">Nee</option></select>
	</section>
	<section id="tab-media" class="tab-panel">
		<p class="sub">Upload JPEG/PNG/WebP of PDF (max 5MB)</p>
		<form method="post" enctype="multipart/form-data">
			<?php echo csrf_field(); ?>
			<input type="file" name="file" class="input" />
			<button class="btn" type="submit">Upload</button>
		</form>
	</section>
	<section id="tab-location" class="tab-panel">
		<label>Latitude</label><input class="input" type="number" step="0.000001" />
		<label>Longitude</label><input class="input" type="number" step="0.000001" />
	</section>
	<section id="tab-tags" class="tab-panel">
		<div class="chips"><span class="chip">ancient</span><span class="chip">architecture</span></div>
	</section>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>


