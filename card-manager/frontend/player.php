<?php
require __DIR__ . '/includes/app.php';

try {
    $idPlayer = id_from_get('id_player');

    if (!$idPlayer) {
        $firstPlayer = fetch_one('SELECT id_player FROM player ORDER BY id_player DESC LIMIT 1');
        $idPlayer = $firstPlayer ? (int) $firstPlayer['id_player'] : null;
    }

    $player = $idPlayer
        ? fetch_one('SELECT * FROM player WHERE id_player = ?', [$idPlayer])
        : null;

    $items = $idPlayer
        ? fetch_all('SELECT * FROM item WHERE id_player = ? ORDER BY id_item ASC', [$idPlayer])
        : [];

    $quests = $idPlayer
        ? fetch_all('SELECT * FROM quest WHERE id_player = ? ORDER BY id_quest ASC', [$idPlayer])
        : [];
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Description Player', 'description-player-page', [
    'css/item_card(second).css',
    'css/quest_card(second).css',
    'css/description_player.css',
]);
?>
  <aside class="description-player description-player--open" aria-label="Detail player">
<?php if (!$player): ?>
    <section class="description-player__summary" aria-label="Ringkasan player">
      <h1 class="description-player__name">Player tidak ditemukan</h1>
      <p class="description-player__empty">Belum ada data player.</p>
    </section>
    <div class="description-player__actions">
      <a class="description-player__return" href="players.php">Return</a>
      <a class="description-player__edit" href="add.php">Add Data</a>
    </div>
<?php else: ?>
    <input class="description-player__tab-input" type="radio" name="player-category" id="tab-inventory" checked />
    <input class="description-player__tab-input" type="radio" name="player-category" id="tab-quest" />

    <section class="description-player__summary" aria-label="Ringkasan player">
      <h1 class="description-player__name"><?= e($player['username']) ?></h1>

      <div class="description-player__line">
        <span class="description-player__marker" aria-hidden="true"></span>
        <span class="description-player__summary-label">Level</span>
        <span class="description-player__level">1</span>
      </div>

      <div class="description-player__line description-player__line--stack">
        <span class="description-player__marker" aria-hidden="true"></span>
        <div>
          <span class="description-player__summary-label">Inventory List</span>
          <ol class="description-player__number-list">
<?php if (!$items): ?>
            <li>Belum ada item</li>
<?php endif; ?>
<?php foreach ($items as $item): ?>
            <li><?= e($item['name']) ?></li>
<?php endforeach; ?>
          </ol>
        </div>
      </div>

      <div class="description-player__line description-player__line--stack">
        <span class="description-player__marker" aria-hidden="true"></span>
        <div>
          <span class="description-player__summary-label">Completed Quest</span>
          <ol class="description-player__completed-list">
<?php if (!$quests): ?>
            <li><strong>Belum ada quest</strong></li>
<?php endif; ?>
<?php foreach ($quests as $quest): ?>
            <li>
              <span class="description-player__check" aria-hidden="true"></span>
              <strong><?= e($quest['title']) ?></strong>
            </li>
<?php endforeach; ?>
          </ol>
        </div>
      </div>
    </section>

    <section class="description-player__category" aria-label="Kategori detail">
      <span class="description-player__category-label">Kategori :</span>
      <div class="description-player__tabs">
        <label class="description-player__tab" for="tab-inventory">Inventory</label>
        <label class="description-player__tab" for="tab-quest">Quest</label>
      </div>
    </section>

    <section class="description-player__content description-player__content--inventory" aria-label="Item inventory">
<?php if (!$items): ?>
      <p class="description-player__empty">Player ini belum punya item.</p>
<?php endif; ?>
<?php foreach ($items as $item): ?>
      <article class="item-card" aria-label="<?= e($item['name']) ?>">
        <a class="app-card-link" href="item.php?id_item=<?= e($item['id_item']) ?>">
          <div class="item-card__image" aria-hidden="true"></div>
          <h2 class="item-card__title"><?= e($item['name']) ?></h2>
        </a>
        <div class="item-card__meta" aria-label="Informasi item">
          <span class="item-card__tag">#</span>
          <span class="item-card__id-label">ID</span>
          <span class="item-card__id-value"><?= e($item['id_item']) ?></span>
        </div>
      </article>
<?php endforeach; ?>
    </section>

    <section class="description-player__content description-player__content--quest" aria-label="Quest selesai">
<?php if (!$quests): ?>
      <p class="description-player__empty">Player ini belum menyelesaikan quest.</p>
<?php endif; ?>
<?php foreach ($quests as $quest): ?>
      <article class="quest-card" aria-label="<?= e($quest['title']) ?>">
        <a class="app-card-link" href="quest.php?id_quest=<?= e($quest['id_quest']) ?>">
          <div class="quest-card__image" aria-hidden="true"></div>
          <h2 class="quest-card__title"><?= e($quest['title']) ?></h2>
        </a>
        <div class="quest-card__meta" aria-label="Informasi quest">
          <span class="quest-card__tag">#</span>
          <span class="quest-card__id-label">ID</span>
          <span class="quest-card__id-value"><?= e($quest['id_quest']) ?></span>
        </div>
      </article>
<?php endforeach; ?>
    </section>

    <div class="description-player__actions">
      <a class="description-player__edit" href="add.php">Edit Player</a>
      <a class="description-player__return" href="players.php">Return</a>
    </div>
<?php endif; ?>
  </aside>
<?php page_end(); ?>
