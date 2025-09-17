<?php
// UI/Pagination (simple numbered)
// Params: $total (int), $current (int), $baseUrl (string) with `?page=` or `&page=` as needed
$total = max(1, (int)($total ?? 1));
$current = max(1, (int)($current ?? 1));
$baseUrl = $baseUrl ?? '?page=';
if ($total <= 1) return;
echo '<nav class="pagination" aria-label="Paginering">';
for ($i=1; $i<=$total; $i++) {
  $href = $baseUrl . $i;
  $cls = 'nav-item' . ($i===$current ? ' active':'');
  echo '<a class="' . $cls . '" href="' . htmlspecialchars((string)$href) . '"' . ($i===$current?' aria-current="page"':'') . '>' . $i . '</a> ';
}
echo '</nav>';

