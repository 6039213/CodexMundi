<?php
require_once __DIR__ . '/../../app/lib/helpers.php';
require_once __DIR__ . '/../../app/lib/auth.php';
require_role(['researcher','admin']);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php include __DIR__ . '/../partials/nav.php'; ?>
<main class="container">
	<h1>Wonder bewerken</h1>
	<div class="form-tabs">
		<button class="tab-btn active" data-target="tab-basic">Basis</button>
		<button class="tab-btn" data-target="tab-history">Historie</button>
		<button class="tab-btn" data-target="tab-media">Media</button>
		<button class="tab-btn" data-target="tab-location">Locatie</button>
		<button class="tab-btn" data-target="tab-tags">Tags</button>
	</div>
	<section id="tab-basic" class="tab-panel active"><p class="sub">Formulieren MVP</p></section>
	<section id="tab-history" class="tab-panel"><p class="sub">Jaar, status</p></section>
	<section id="tab-media" class="tab-panel"><p class="sub">Upload/overzicht</p></section>
	<section id="tab-location" class="tab-panel"><p class="sub">GPS</p></section>
	<section id="tab-tags" class="tab-panel"><p class="sub">Koppelingen</p></section>
</main>
<?php include __DIR__ . '/../partials/footer.php'; ?>


