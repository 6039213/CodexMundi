<div class="card">
  <h2 style="margin-top:0">Wereldkaart</h2>
  <div class="muted" style="margin-bottom:.5rem">Klik op een marker voor details. Klassiek = rood, modern = blauw.</div>
  <div class="flex" style="gap:.5rem; align-items:center; margin-bottom:.5rem">
    <input id="map-search" placeholder="Zoek op naam" style="max-width:260px">
    <label class="muted">Filters:</label>
    <label><input type="checkbox" id="filter-classic" checked> klassiek</label>
    <label><input type="checkbox" id="filter-modern" checked> modern</label>
    <button class="btn" id="map-reset" type="button">Reset view</button>
    <button class="btn" id="map-locate" type="button">Mijn locatie</button>
  </div>
  <div id="map" style="height: 65vh; min-height: 420px; border-radius:12px; overflow:hidden; border:1px solid var(--border)"></div>
  <noscript>
    <p>JavaScript is uitgeschakeld.</p>
  </noscript>
</div>

<script>
(function(){
  // Inject Leaflet CSS/JS from CDN (browser fetches)
  var leafletCss = document.createElement('link');
  leafletCss.rel = 'stylesheet';
  leafletCss.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
  document.head.appendChild(leafletCss);
  var s = document.createElement('script');
  s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
  s.async = true;
  s.onload = initMap;
  s.onerror = function(){ /* fallback: nothing extra */ };
  document.head.appendChild(s);

  function initMap(){
    if (!document.getElementById('map') || !window.L) return;
    var map = L.map('map', { scrollWheelZoom: true });

    // Themed tiles (light/dark)
    function themeName(){ try{ return document.documentElement.dataset.theme || 'light'; }catch(e){ return 'light'; } }
    function makeTiles(){
      var isDark = themeName() === 'dark';
      var url = isDark
        ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
        : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
      var attr = '&copy; OpenStreetMap, &copy; CARTO';
      return L.tileLayer(url, { attribution: attr, maxZoom: 19 });
    }
    var tiles = makeTiles().addTo(map);

    // React to theme toggle
    try{
      new MutationObserver(function(){ map.removeLayer(tiles); tiles = makeTiles().addTo(map); })
        .observe(document.documentElement, { attributes:true, attributeFilter:['data-theme'] });
    }catch(e){}

    var pts = <?php echo json_encode($points, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
    var markers = [];
    var markersById = {};
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
        fillOpacity: 0.85
      }).addTo(map);
      var url = <?php echo json_encode($base, JSON_UNESCAPED_SLASHES); ?> + '/wonders/' + parseInt(p.id,10);
      m.bindPopup('<strong>'+escapeHtml(p.name)+'</strong><br><a href="'+url+'">Bekijk details</a>');
      m.__data = p;
      markers.push(m);
      markersById[p.id] = m;
    });

    if (markers.length){
      var group = L.featureGroup(markers);
      map.fitBounds(group.getBounds().pad(0.2));
    } else {
      map.setView([20, 0], 2);
    }

    // Filtering
    var chkClassic = document.getElementById('filter-classic');
    var chkModern = document.getElementById('filter-modern');
    function applyFilters(){
      var showClassic = !chkClassic || chkClassic.checked;
      var showModern = !chkModern || chkModern.checked;
      var q = (document.getElementById('map-search')?.value || '').toLowerCase();
      markers.forEach(function(m){
        var t = (m.__data.type||'').toLowerCase();
        var name = (m.__data.name||'').toLowerCase();
        var okType = (t==='classic' ? showClassic : t==='modern' ? showModern : true);
        var okSearch = (!q || name.indexOf(q) !== -1);
        var visible = okType && okSearch;
        if (visible) { m.addTo(map); } else { map.removeLayer(m); }
      });
    }
    ['change','input','keyup'].forEach(function(ev){
      if (chkClassic) chkClassic.addEventListener(ev, applyFilters);
      if (chkModern) chkModern.addEventListener(ev, applyFilters);
      var search = document.getElementById('map-search');
      if (search) search.addEventListener(ev, applyFilters);
    });

    // Locate + reset
    var btnReset = document.getElementById('map-reset');
    if (btnReset) btnReset.addEventListener('click', function(){
      try{
        var group = L.featureGroup(markers.filter(function(m){ return map.hasLayer(m); }));
        if (group.getLayers().length){ map.fitBounds(group.getBounds().pad(0.2)); }
      }catch(e){}
    });
    var btnLocate = document.getElementById('map-locate');
    if (btnLocate) btnLocate.addEventListener('click', function(){
      if (!navigator.geolocation) return;
      navigator.geolocation.getCurrentPosition(function(pos){
        var latlng = [pos.coords.latitude, pos.coords.longitude];
        L.circleMarker(latlng, {radius:7,color:'#22C55E',fillColor:'#22C55E',fillOpacity:.9}).addTo(map).bindPopup('Je bent hier').openPopup();
        map.setView(latlng, 8);
      });
    });

    applyFilters();
    L.control.scale({imperial:false}).addTo(map);
  }

  function escapeHtml(str){
    return String(str).replace(/[&<>\"']/g, function(s){
      return ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;','\'':'&#39;'})[s];
    });
  }
})();
</script>

