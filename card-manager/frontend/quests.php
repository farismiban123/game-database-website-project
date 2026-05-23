<?php
require __DIR__ . '/includes/app.php';

try {
    $quests = fetch_all(
        'SELECT quest.*, player.username
         FROM quest
         LEFT JOIN player ON player.id_player = quest.id_player
         ORDER BY quest.id_quest DESC'
    );
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Quests', 'quest-card-page', ['css/quest_card(second).css']);
app_header();
?>
  <main class="quest-card-list" aria-live="polite">
<?php if (!$quests): ?>
    <p class="quest-card-status">Belum ada data quest.</p>
<?php endif; ?>

<?php foreach ($quests as $quest): ?>
    <article class="quest-card" aria-label="<?= e($quest['title']) ?>">
      <a class="app-card-link" href="quest.php?id_quest=<?= e($quest['id_quest']) ?>">
        <div class="quest-card__image" aria-hidden="true"></div>
        <h1 class="quest-card__title"><?= e($quest['title']) ?></h1>
      </a>

      <div class="quest-card__meta" aria-label="Informasi quest">
        <span class="quest-card__tag">#</span>
        <span class="quest-card__id-label">ID</span>
        <span class="quest-card__id-value"><?= e($quest['id_quest']) ?></span>
      </div>

      <div class="quest-card__actions">
        <a class="quest-card__description" href="quest.php?id_quest=<?= e($quest['id_quest']) ?>">Description</a>
      </div>
    </article>
<?php endforeach; ?>
  </main>
<?php page_end(); ?>
