<div class="card">
  <h2>Nieuw wereldwonder</h2>
<form method="post" action="<?php echo $base; ?>/wonders">
    <label>Naam</label><input name="name" required>
    <label>Korte beschrijving</label><textarea name="short_description"></textarea>
    <label>Jaar</label><input type="number" name="year">
    <label>Werelddeel</label>
    <select name="continent">
      <option value="">- Kies -</option>
      <option>Africa</option><option>Europe</option><option>Asia</option><option>North America</option><option>South America</option><option>Oceania</option>
    </select>
    <label>Type</label>
    <select name="type"><option value="classic">klassiek</option><option value="modern">modern</option></select>
    <label><input type="checkbox" name="exists_now" checked> Bestaat nog</label>
    <label>Mythe</label><textarea name="myth"></textarea>
    <label>Verhaal</label><textarea name="story"></textarea>
    <label>Latitude</label><input type="number" step="any" name="lat">
    <label>Longitude</label><input type="number" step="any" name="lng">
    <button>Aanmaken</button>
  </form>
</div>
