<?php
require_once __DIR__ . '/../app/lib/helpers.php';
?>
<?php include __DIR__ . '/partials/header.php'; ?>
<?php include __DIR__ . '/partials/nav.php'; ?>
<main class="container">
	<h1>Wereldkaart</h1>
	<div id="map" style="height:80vh;margin-top:12px"></div>
</main>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9C3s=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
document.addEventListener('DOMContentLoaded', async () => {
	const map = L.map('map').setView([20, 0], 2);
	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19}).addTo(map);
	try{
		const res = await fetch('/public/map_points.php');
		const points = await res.json();
		points.forEach(p=>{
			if(!p.lat || !p.lng) return;
			const m = L.marker([p.lat, p.lng]);
			m.bindPopup(`<a href="/public/wonder.php?slug=${p.slug}">${p.title}</a>`);
			m.addTo(map);
		});
	}catch(e){ console.error(e); }
});
</script>
<?php include __DIR__ . '/partials/footer.php'; ?>


