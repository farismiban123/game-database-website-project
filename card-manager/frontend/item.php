<?php
require __DIR__ . '/includes/app.php';

try {
    $idItem = id_from_get('id_item');
    $item = $idItem
        ? fetch_one(
            'SELECT item.*, player.username
             FROM item
             LEFT JOIN player ON player.id_player = item.id_player
             WHERE item.id_item = ?',
            [$idItem]
        )
        : null;

    $rawItems = $idItem
        ? fetch_all('SELECT * FROM raw_item WHERE id_item = ? ORDER BY id_raw_item ASC', [$idItem])
        : [];
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Detail Item', 'item-card-page', ['css/item_card(second).css']);
app_header();
?>
  <main class="app-shell app-detail">
<?php if (!$item): ?>
    <section class="app-message">
      <h1>Item tidak ditemukan</h1>
      <p>Data item tidak tersedia di database.</p>
      <a class="app-button app-button--primary" href="items.php">Kembali ke Items</a>
    </section>
<?php else: ?>
    <section class="app-detail__layout">
      <article class="item-card" aria-label="<?= e($item['name']) ?>">
        <div class="item-card__image" aria-hidden="true"></div>
        <h1 class="item-card__title"><?= e($item['name']) ?></h1>
        <div class="item-card__meta" aria-label="Informasi item">
          <span class="item-card__tag">#</span>
          <span class="item-card__id-label">ID</span>
          <span class="item-card__id-value"><?= e($item['id_item']) ?></span>
        </div>
        <div class="item-card__actions">
          <a class="item-card__description" href="items.php">Back</a>
        </div>
      </article>

      <section class="app-detail__box">
        <h1><?= e($item['name']) ?></h1>
        <p><?= e($item['description'] ?: 'Tidak ada deskripsi.') ?></p>

        <dl class="app-meta-list">
          <div>
            <dt>Owner</dt>
            <dd><?= e($item['username'] ?: '-') ?></dd>
          </div>
          <div>
            <dt>ID Player</dt>
            <dd><?= e($item['id_player'] ?: '-') ?></dd>
          </div>
        </dl>

        <h2>Raw Item</h2>
<?php if (!$rawItems): ?>
        <p>Belum ada raw item untuk item ini.</p>
<?php else: ?>
        <dl class="app-meta-list">
<?php foreach ($rawItems as $rawItem): ?>
          <div>
            <dt><?= e($rawItem['title']) ?></dt>
            <dd><?= e($rawItem['description']) ?></dd>
          </div>
<?php endforeach; ?>
        </dl>
<?php endif; ?>
      </section>
    </section>
<?php endif; ?>
  </main>
<?php page_end(); ?>
