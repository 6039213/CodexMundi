<div class="card">
  <h2 style="margin-top:0">Inloggen</h2>
  <form method="post" action="<?php echo $base; ?>/login" class="space-y">
    <label for="email">E-mail</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Wachtwoord</label>
    <input type="password" name="password" id="password" required>
    <div class="form-actions">
      <button class="btn btn-primary">Inloggen</button>
      <a class="btn" href="<?php echo $base; ?>/register">Account aanmaken</a>
    </div>
  </form>
</div>

<div class="card">
  <h3 style="margin-top:0">Rollen & rechten</h3>
  <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(240px,1fr))">
    <div>
      <strong>Bezoeker</strong>
      <ul>
        <li>Overzicht en details bekijken</li>
        <li>Zoeken/filteren/sorteren</li>
        <li>Kaartweergave</li>
      </ul>
    </div>
    <div>
      <strong>Onderzoeker</strong>
      <ul>
        <li>Wonderen aanmaken/bewerken (eigen)</li>
        <li>Foto's uploaden</li>
        <li>Eigen werk exporteren</li>
      </ul>
    </div>
    <div>
      <strong>Archivaris</strong>
      <ul>
        <li>Historische velden wijzigen</li>
        <li>Documenten toevoegen</li>
        <li>Locatie (GPS) beheren</li>
      </ul>
    </div>
    <div>
      <strong>Redacteur</strong>
      <ul>
        <li>Foto's en wonderen goedkeuren</li>
        <li>Tags beheren</li>
        <li>Wachtrij & statistieken</li>
      </ul>
    </div>
    <div>
      <strong>Beheerder</strong>
      <ul>
        <li>Alle rechten + gebruikers/rollen</li>
        <li>Upload- en beveiligingsinstellingen</li>
        <li>Audit logs en export</li>
      </ul>
  </div>
  </div>
</div>
