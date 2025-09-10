<div class="card">
  <h2>Registreren</h2>
  <form method="post" action="<?php echo $base; ?>/register">
    <label for="name">Naam</label>
    <input type="text" name="name" id="name" required>
    <label for="email">E-mail</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Wachtwoord</label>
    <input type="password" name="password" id="password" required>
    <button>Account aanmaken</button>
  </form>
</div>
