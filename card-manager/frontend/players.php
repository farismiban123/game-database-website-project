<?php
require __DIR__ . '/includes/app.php';

try {
    $players = fetch_all('SELECT * FROM player ORDER BY id_player DESC');
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Players', 'player-card-page', ['css/player_card.css']);
app_header();
?>
  <main class="player-card-list" aria-live="polite">
<?php if (!$players): ?>
    <p class="player-card-status">Belum ada data player.</p>
<?php endif; ?>

<?php foreach ($players as $player): ?>
    <article class="player-card" aria-label="Player <?= e($player['username']) ?>">
      <div class="player-card__content">
        <div class="player-card__image" aria-hidden="true"></div>

        <div class="player-card__details">
          <div class="player-card__info player-card__info--username">
            <span class="player-card__icon" aria-hidden="true"></span>
            <div class="player-card__text">
              <span class="player-card__label">Username</span>
              <strong class="player-card__value"><?= e($player['username']) ?></strong>
            </div>
          </div>

          <div class="player-card__info player-card__info--id">
            <span class="player-card__tag">#</span>
            <span class="player-card__id-label">ID</span>
            <span class="player-card__id-value"><?= e($player['id_player']) ?></span>
          </div>

          <div class="player-card__info player-card__info--date">
            <span class="player-card__icon" aria-hidden="true"></span>
            <div class="player-card__text">
              <span class="player-card__label">Join Date</span>
              <strong class="player-card__value"><?= e(format_date_id($player['join_date'])) ?></strong>
            </div>
          </div>
        </div>
      </div>

      <div class="player-card__actions">
        <a class="player-card__info-button" href="player.php?id_player=<?= e($player['id_player']) ?>">Info Player</a>
        <a class="player-card__info-button" href="add.php">Add Data</a>
      </div>
    </article>
<?php endforeach; ?>
  </main>
<?php page_end(); ?>
