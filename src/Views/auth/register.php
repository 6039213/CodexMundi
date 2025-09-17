<div class="card">
  <h2 style="margin-top:0">Registreren</h2>
  <form method="post" action="<?php echo $base; ?>/register" class="space-y">
    <label for="name">Naam</label>
    <input type="text" name="name" id="name" required>
    <label for="email">E-mail</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Wachtwoord</label>
    <input type="password" name="password" id="password" required>
    <div class="form-actions">
      <button class="btn btn-primary">Account aanmaken</button>
      <a class="btn" href="<?php echo $base; ?>/login">Inloggen</a>
    </div>
  </form>
</div>
