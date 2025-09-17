<div class="card">
  <h2 style="margin-top:0">Gebruikersbeheer</h2>
  <h3>Nieuwe gebruiker</h3>
  <form method="post" action="<?php echo $base; ?>/admin/users/create" class="grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))">
    <div>
      <label>Naam</label>
      <input name="name" required>
    </div>
    <div>
      <label>E-mail</label>
      <input type="email" name="email" required>
    </div>
    <div>
      <label>Wachtwoord</label>
      <input type="password" name="password" required>
    </div>
    <div>
      <label>Rol</label>
      <select name="role">
        <?php foreach ($roles as $r): ?>
          <option value="<?php echo htmlspecialchars($r) ?>"><?php echo htmlspecialchars($r) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div style="align-self:end">
      <button class="btn btn-primary">Aanmaken</button>
    </div>
  </form>
  <hr>
  <table>
    <thead><tr><th>ID</th><th>Naam</th><th>E-mail</th><th>Rol</th><th>Actie</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?php echo (int)$u['id'] ?></td>
          <td><?php echo htmlspecialchars($u['name']) ?></td>
          <td><?php echo htmlspecialchars($u['email']) ?></td>
          <td>
            <form method="post" action="<?php echo $base; ?>/admin/users/<?php echo (int)$u['id'] ?>/role" class="inline">
              <select name="role">
                <?php foreach ($roles as $r): ?>
                  <option value="<?php echo htmlspecialchars($r) ?>" <?php echo ($u['role']===$r?'selected':'') ?>><?php echo htmlspecialchars($r) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-primary">Opslaan</button>
            </form>
          </td>
          <td></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
