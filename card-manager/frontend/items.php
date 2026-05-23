<?php
require __DIR__ . '/includes/app.php';

try {
    $items = fetch_all(
        'SELECT item.*, player.username
         FROM item
         LEFT JOIN player ON player.id_player = item.id_player
         ORDER BY item.id_item DESC'
    );
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Items', 'item-card-page', ['css/item_card(second).css']);
app_header();
?>
  <main class="item-card-list" aria-live="polite">
<?php if (!$items): ?>
    <p class="item-card-status">Belum ada data item.</p>
<?php endif; ?>

<?php foreach ($items as $item): ?>
    <article class="item-card" aria-label="<?= e($item['name']) ?>">
      <a class="app-card-link" href="item.php?id_item=<?= e($item['id_item']) ?>">
        <div class="item-card__image" aria-hidden="true"></div>
        <h1 class="item-card__title"><?= e($item['name']) ?></h1>
      </a>

      <div class="item-card__meta" aria-label="Informasi item">
        <span class="item-card__tag">#</span>
        <span class="item-card__id-label">ID</span>
        <span class="item-card__id-value"><?= e($item['id_item']) ?></span>
      </div>

      <div class="item-card__actions">
        <a class="item-card__description" href="item.php?id_item=<?= e($item['id_item']) ?>">Description</a>
      </div>
    </article>
<?php endforeach; ?>
  </main>
<?php page_end(); ?>
