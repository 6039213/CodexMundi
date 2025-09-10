<div class="card">
  <h2>Audit log (laatste 100)</h2>
  <table>
    <thead><tr><th>ID</th><th>Gebruiker</th><th>Actie</th><th>Entiteit</th><th>Entiteit ID</th><th>Tijd</th><th>Details</th></tr></thead>
    <tbody>
      <?php foreach ($logs as $l): ?>
        <tr>
          <td><?php echo (int)$l['id'] ?></td>
          <td><?php echo htmlspecialchars($l['user_name'] ?? '-') ?></td>
          <td><?php echo htmlspecialchars($l['action']) ?></td>
          <td><?php echo htmlspecialchars($l['entity']) ?></td>
          <td><?php echo htmlspecialchars((string)$l['entity_id']) ?></td>
          <td><?php echo htmlspecialchars($l['created_at']) ?></td>
          <td class="muted"><?php echo htmlspecialchars($l['details'] ?? '') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

