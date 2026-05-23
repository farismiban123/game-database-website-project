<?php
require __DIR__ . '/includes/app.php';

try {
    $playerCount = (int) fetch_one('SELECT COUNT(*) AS total FROM player')['total'];
    $itemCount = (int) fetch_one('SELECT COUNT(*) AS total FROM item')['total'];
    $questCount = (int) fetch_one('SELECT COUNT(*) AS total FROM quest')['total'];
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Card Manager', 'app-page');
app_header();
?>
  <main class="app-shell">
    <section class="app-dashboard" aria-label="Ringkasan database">
      <article class="app-panel">
        <h1>Players</h1>
        <span class="app-stat"><?= e($playerCount) ?></span>
        <p>Daftar player yang tersimpan di database.</p>
        <a class="app-button app-button--primary" href="players.php">Buka Players</a>
      </article>

      <article class="app-panel">
        <h1>Items</h1>
        <span class="app-stat"><?= e($itemCount) ?></span>
        <p>Inventory item yang terhubung ke player.</p>
        <a class="app-button app-button--primary" href="items.php">Buka Items</a>
      </article>

      <article class="app-panel">
        <h1>Quests</h1>
        <span class="app-stat"><?= e($questCount) ?></span>
        <p>Quest yang tersimpan dan bisa dilihat per player.</p>
        <a class="app-button app-button--primary" href="quests.php">Buka Quests</a>
      </article>

      <article class="app-panel">
        <h1>Add Data</h1>
        <span class="app-stat">+</span>
        <p>Tambah player, item, atau quest tanpa JavaScript.</p>
        <a class="app-button app-button--primary" href="add.php">Tambah Data</a>
      </article>
    </section>
  </main>
<?php page_end(); ?>
