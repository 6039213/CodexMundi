<?php
require_once __DIR__ . '/../app/lib/helpers.php';
require_once __DIR__ . '/../app/models/Wonder.php';
$config = require __DIR__ . '/../app/config.php';
$featured = Wonder::featured(6);
$counts = Wonder::countsByContinent();
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main id="main" class="container">
	<section class="hero" aria-label="Zoek">
		<h1>Codex Mundi</h1>
		<p class="sub">Digitaal archief van 21 wereldwonderen</p>
                <form action="/wonders.php" method="get" style="margin-top:16px">
			<div class="searchbar" role="search">
				<svg width="18" height="18" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M21 21l-4.35-4.35" stroke="#6ea8fe" stroke-width="2"/><circle cx="11" cy="11" r="7" stroke="#6ea8fe" stroke-width="2"/></svg>
				<input id="search" name="q" placeholder="Zoek wereldwonder, land of mythe..." aria-label="Zoek" />
			</div>
		</form>
		<div class="chips" style="margin-top:12px">
                        <a class="chip" href="/wonders.php?continent=africa">Africa (<?php echo $counts['africa']??0; ?>)</a>
                        <a class="chip" href="/wonders.php?continent=asia">Asia (<?php echo $counts['asia']??0; ?>)</a>
                        <a class="chip" href="/wonders.php?continent=europe">Europe (<?php echo $counts['europe']??0; ?>)</a>
                        <a class="chip" href="/wonders.php?continent=north_america">N. America (<?php echo $counts['north_america']??0; ?>)</a>
                        <a class="chip" href="/wonders.php?continent=south_america">S. America (<?php echo $counts['south_america']??0; ?>)</a>
                        <a class="chip" href="/wonders.php?continent=oceania">Oceania (<?php echo $counts['oceania']??0; ?>)</a>
		</div>
	</section>
	<section style="margin:24px 0">
		<h2>Uitgelicht</h2>
		<div class="grid grid-3">
			<?php foreach ($featured as $w): ?>
				<article class="card">
                                        <a href="/wonder.php?slug=<?php echo e($w['slug']); ?>">
                                                <img class="card-cover" src="/assets/img/wonders/<?php echo e($w['slug']); ?>.jpg" alt="<?php echo e($w['title']); ?>" />
					</a>
					<div class="content">
						<h3><?php echo e($w['title']); ?></h3>
						<p class="sub"><?php echo e(ucfirst($w['continent'])); ?> • <?php echo e($w['country']); ?></p>
                                                <a class="btn" href="/wonder.php?slug=<?php echo e($w['slug']); ?>">Bekijken</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>
