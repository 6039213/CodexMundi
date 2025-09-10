<div class="card">
  <h2>Wereldkaart</h2>
  <p class="muted">Klik op een marker voor details. Klassiek = rood, modern = blauw.</p>
  <div id="map" style="height: 520px; border-radius:12px; overflow:hidden; border:1px solid var(--border)"></div>
  <noscript>
    <p>JavaScript is uitgeschakeld. Hieronder staat een lijst met coördinaten.</p>
  </noscript>
</div>

<div class="card">
  <h3>Overzicht (tabel)</h3>
  <table>
    <thead><tr><th>Naam</th><th>Type</th><th>Continent</th><th>Latitude</th><th>Longitude</th></tr></thead>
    <tbody>
      <?php foreach ($points as $p): ?>
        <tr>
          <td><a href="<?php echo $base; ?>/wonders/<?php echo (int)$p['id'] ?>"><?php echo htmlspecialchars($p['name']) ?></a></td>
          <td><?php echo htmlspecialchars($p['type'] ?? '') ?></td>
          <td><?php echo htmlspecialchars($p['continent'] ?? '') ?></td>
          <td><?php echo htmlspecialchars((string)$p['lat']) ?></td>
          <td><?php echo htmlspecialchars((string)$p['lng']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  </div>

<script>
(function(){
  // Inject Leaflet CSS/JS from CDN (browser fetches; offline falls back to table)
  var leafletCss = document.createElement('link');
  leafletCss.rel = 'stylesheet';
  leafletCss.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
  document.head.appendChild(leafletCss);
  var s = document.createElement('script');
  s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
  s.async = true;
  s.onload = initMap;
  s.onerror = function(){ /* graceful fallback: table already shown */ };
  document.head.appendChild(s);

  function initMap(){
    if (!document.getElementById('map') || !window.L) return;
    var map = L.map('map', { scrollWheelZoom: true });
    var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var pts = <?php echo json_encode($points, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
    var markers = [];
    var colors = { classic: '#d32f2f', modern: '#1976d2' };
    pts.forEach(function(p){
      var lat = parseFloat(p.lat), lng = parseFloat(p.lng);
      if (!isFinite(lat) || !isFinite(lng)) return;
      var color = colors[(p.type||'').toLowerCase()] || '#5c6bc0';
      var m = L.circleMarker([lat, lng], {
        radius: 8,
        color: color,
        weight: 2,
        opacity: 1,
        fillColor: color,
        fillOpacity: 0.8
      }).addTo(map);
      var url = <?php echo json_encode($base, JSON_UNESCAPED_SLASHES); ?> + '/wonders/' + parseInt(p.id,10);
      m.bindPopup('<strong>'+escapeHtml(p.name)+'</strong><br><a href="'+url+'">Bekijk details</a>');
      markers.push(m);
    });

    if (markers.length){
      var group = L.featureGroup(markers);
      map.fitBounds(group.getBounds().pad(0.2));
    } else {
      map.setView([20, 0], 2);
    }
  }

  function escapeHtml(str){
    return String(str).replace(/[&<>\"']/g, function(s){
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;','\'':'&#39;'})[s];
    });
  }
})();
</script>

