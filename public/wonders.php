<?php
require_once __DIR__ . '/../app/lib/helpers.php';
require_once __DIR__ . '/../app/controllers/WonderController.php';
$config = require __DIR__ . '/../app/config.php';
$data = WonderController::index();
$items = $data['items'];
$total = $data['total'];
$page = $data['page'];
$limit = $data['limit'];
$pages = max(1, (int)ceil($total / $limit));
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main id="main" class="container">
	<h1>Wonders</h1>
	<div class="layout" role="region" aria-label="Wonders overzicht">
		<aside class="sidebar" aria-label="Filters">
			<form method="get">
				<div class="group">
					<label for="q">Zoek</label>
					<div class="searchbar"><svg width="18" height="18" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M21 21l-4.35-4.35" stroke="#6ea8fe" stroke-width="2"/><circle cx="11" cy="11" r="7" stroke="#6ea8fe" stroke-width="2"/></svg><input id="search" name="q" value="<?php echo e($_GET['q'] ?? ''); ?>" placeholder="Zoek wereldwonder, land of mythe..." /></div>
				</div>
				<div class="group filter">
					<h3>Categorie</h3>
					<label><input type="radio" name="category" value="" <?php echo empty($_GET['category'])?'checked':''; ?>> Alles</label>
					<label><input type="radio" name="category" value="classic" <?php echo (($_GET['category']??'')==='classic')?'checked':''; ?>> Classic</label>
					<label><input type="radio" name="category" value="modern" <?php echo (($_GET['category']??'')==='modern')?'checked':''; ?>> Modern</label>
					<label><input type="radio" name="category" value="natural" <?php echo (($_GET['category']??'')==='natural')?'checked':''; ?>> Natural</label>
				</div>
				<div class="group filter">
					<h3>Continent</h3>
					<?php $conts=['africa','asia','europe','north_america','south_america','oceania','antarctica']; foreach($conts as $c): ?>
						<label><input type="radio" name="continent" value="<?php echo $c; ?>" <?php echo (($_GET['continent']??'')===$c)?'checked':''; ?>> <?php echo ucfirst(str_replace('_',' ',$c)); ?></label>
					<?php endforeach; ?>
					<label><input type="radio" name="continent" value="" <?php echo empty($_GET['continent'])?'checked':''; ?>> Alles</label>
				</div>
				<div class="group filter">
					<h3>Bestaat</h3>
					<label><input type="radio" name="exists" value="" <?php echo !isset($_GET['exists'])?'checked':''; ?>> Ongeacht</label>
					<label><input type="radio" name="exists" value="1" <?php echo (($_GET['exists']??'')==='1')?'checked':''; ?>> Ja</label>
					<label><input type="radio" name="exists" value="0" <?php echo (($_GET['exists']??'')==='0')?'checked':''; ?>> Nee</label>
				</div>
				<button class="btn" type="submit">Filter toepassen</button>
			</form>
		</aside>
		<section>
			<div class="controls" aria-label="Lijstopties">
				<div class="toggle" role="tablist" aria-label="Weergave">
					<button id="view-grid" class="active" aria-pressed="true">Grid</button>
					<button id="view-list" aria-pressed="false">List</button>
				</div>
				<div>
					<label for="sort" class="sub">Sorteer</label>
					<select id="sort" name="sort" onchange="location.search=new URLSearchParams({...Object.fromEntries(new URLSearchParams(location.search)), sort:this.value}).toString()">
						<option value="name" <?php echo (($_GET['sort']??'')==='name')?'selected':''; ?>>Naam</option>
						<option value="year" <?php echo (($_GET['sort']??'')==='year')?'selected':''; ?>>Jaartal</option>
						<option value="continent" <?php echo (($_GET['sort']??'')==='continent')?'selected':''; ?>>Continent</option>
					</select>
				</div>
			</div>
			<div id="wonder-list" class="grid grid-3 card-list" aria-live="polite">
				<?php foreach ($items as $w): ?>
					<article class="card" data-title="<?php echo e($w['title']); ?>" data-category="<?php echo e($w['category']); ?>" data-continent="<?php echo e($w['continent']); ?>" data-year="<?php echo (int)$w['year_built']; ?>" data-exists="<?php echo (int)$w['exists_now']; ?>">
						<a href="/public/wonder.php?slug=<?php echo e($w['slug']); ?>" aria-label="Bekijk <?php echo e($w['title']); ?>">
							<img class="card-cover" src="/public/assets/img/wonders/<?php echo e($w['slug']); ?>.jpg" alt="<?php echo e($w['title']); ?>" />
						</a>
						<div class="content">
							<h3><?php echo e($w['title']); ?></h3>
							<p class="sub"><?php echo e(ucfirst($w['continent'])); ?> • <?php echo e($w['country']); ?> • <?php echo $w['year_built'] ? (int)$w['year_built'] : '—'; ?></p>
							<div class="chips" aria-label="Labels">
								<span class="chip"><?php echo e($w['category']); ?></span>
								<span class="badge <?php echo $w['exists_now']?'green':'red'; ?>"><?php echo $w['exists_now']?'Bestaat nog':'Bestaat niet'; ?></span>
							</div>
							<a class="btn" href="/public/wonder.php?slug=<?php echo e($w['slug']); ?>">Bekijken</a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<nav class="pagination" aria-label="Paginatie">
				<?php if ($page > 1): ?><a href="?<?php $q = $_GET; $q['page']=$page-1; echo http_build_query($q); ?>">Vorige</a><?php endif; ?>
				<span class="sub">Pagina <?php echo $page; ?> / <?php echo $pages; ?></span>
				<?php if ($page < $pages): ?><a href="?<?php $q = $_GET; $q['page']=$page+1; echo http_build_query($q); ?>">Volgende</a><?php endif; ?>
			</nav>
		</section>
	</div>
</main>
<?php include __DIR__ . '/partials/footer.php'; ?>


