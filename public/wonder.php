<?php
require_once __DIR__ . '/../app/lib/helpers.php';
require_once __DIR__ . '/../app/controllers/WonderController.php';
$slug = $_GET['slug'] ?? '';
$data = $slug ? WonderController::show($slug) : null;
if (!$data) { http_response_code(404); exit('Niet gevonden'); }
$w = $data['wonder'];
$media = $data['media'];
$related = $data['related'];
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main id="main" class="container">
	<figure class="card" style="overflow:hidden">
                <img class="card-cover" src="/assets/img/wonders/<?php echo e($w['slug']); ?>.jpg" alt="<?php echo e($w['title']); ?> headerbeeld" />
	</figure>
	<header style="margin:16px 0">
		<h1><?php echo e($w['title']); ?></h1>
		<p class="sub chips">
			<span class="chip"><?php echo e($w['country']); ?></span>
			<span class="chip"><?php echo e(ucfirst($w['continent'])); ?></span>
			<span class="chip"><?php echo $w['year_built'] ? (int)$w['year_built'] : '—'; ?></span>
			<span class="badge <?php echo $w['exists_now']?'green':'red'; ?>"><?php echo $w['exists_now']?'Bestaat nog':'Bestaat niet'; ?></span>
		</p>
	</header>
	<div class="tabs">
		<div class="form-tabs">
			<button class="tab-btn active" data-target="tab-overview">Overzicht</button>
			<button class="tab-btn" data-target="tab-gallery">Galerij</button>
			<button class="tab-btn" data-target="tab-map">Locatie</button>
			<button class="tab-btn" data-target="tab-myth">Mythe/Verhaal</button>
		</div>
		<section id="tab-overview" class="tab-panel active">
			<h3>Samenvatting</h3>
			<p><?php echo nl2br(e($w['summary'])); ?></p>
			<h3>Beschrijving</h3>
			<p><?php echo nl2br(e($w['description'])); ?></p>
		</section>
		<section id="tab-gallery" class="tab-panel" data-lightbox>
			<div class="grid grid-3">
				<?php foreach ($media as $m): if ($m['type']!=='image') continue; ?>
					<img src="<?php echo e($m['url']); ?>" alt="<?php echo e($w['title']); ?> media" />
				<?php endforeach; ?>
			</div>
		</section>
		<section id="tab-map" class="tab-panel">
			<div id="map" style="height:600px"></div>
		</section>
		<section id="tab-myth" class="tab-panel">
			<p><?php echo nl2br(e($w['myth'])); ?></p>
		</section>
	</div>
	<section style="margin-top:24px">
		<h3>Gerelateerde wonderen</h3>
		<div class="grid grid-3">
			<?php foreach ($related as $r): ?>
                                <article class="card">
                                        <a href="/wonder.php?slug=<?php echo e($r['slug']); ?>">
                                                <img class="card-cover" src="/assets/img/wonders/<?php echo e($r['slug']); ?>.jpg" alt="<?php echo e($r['title']); ?>" />
					</a>
					<div class="content"><h4><?php echo e($r['title']); ?></h4></div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
</main>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9C3s=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
	var lat = <?php echo $w['lat']? (float)$w['lat'] : 'null'; ?>;
	var lng = <?php echo $w['lng']? (float)$w['lng'] : 'null'; ?>;
	if(lat && lng){
		var map = L.map('map').setView([lat,lng], 6);
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
		var marker = L.marker([lat,lng], {title: '<?php echo e($w['title']); ?>'}).addTo(map);
		marker.bindPopup('<?php echo e($w['title']); ?>');
	}
});
</script>
<?php include __DIR__ . '/partials/footer.php'; ?>


