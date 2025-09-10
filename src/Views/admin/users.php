<div class="card">
  <h2>Gebruikersbeheer</h2>
  <table>
    <thead><tr><th>ID</th><th>Naam</th><th>E-mail</th><th>Rol</th><th>Actie</th></tr></thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?php echo (int)$u['id'] ?></td>
          <td><?php echo htmlspecialchars($u['name']) ?></td>
          <td><?php echo htmlspecialchars($u['email']) ?></td>
          <td>
            <form method="post" action="<?php echo $base; ?>/admin/users/<?php echo (int)$u['id'] ?>/role">
              <select name="role">
                <?php foreach ($roles as $r): ?>
                  <option value="<?php echo htmlspecialchars($r) ?>" <?php echo ($u['role']===$r?'selected':'') ?>><?php echo htmlspecialchars($r) ?></option>
                <?php endforeach; ?>
              </select>
              <button>Opslaan</button>
            </form>
          </td>
          <td></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
