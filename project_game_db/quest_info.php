<?php
$quest_info_id = (int) ($_GET['quest_info_id'] ?? 0);

if ($quest_info_id <= 0) {
    return;
}

$info_stmt = mysqli_prepare($conn, "SELECT * FROM quest WHERE quest_id = ?");
mysqli_stmt_bind_param($info_stmt, "i", $quest_info_id);
mysqli_stmt_execute($info_stmt);
$info_quest = mysqli_fetch_assoc(mysqli_stmt_get_result($info_stmt));

if (!$info_quest) {
    return;
}

$related_stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM quest
     WHERE quest_difficulty = ?
     ORDER BY quest_id DESC
     LIMIT 8"
);

$related_stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM quest
     WHERE quest_reward = ?
     ORDER BY quest_id DESC
     LIMIT 8"
);

mysqli_stmt_bind_param($related_stmt, "s", $info_quest['quest_difficulty']);
mysqli_stmt_bind_param($related_stmt, "s", $info_quest['quest_reward']);
mysqli_stmt_execute($related_stmt);
$related_query = mysqli_stmt_get_result($related_stmt);

?>

<div class="item-info-backdrop" onclick="location.href='quest_page.php'"></div>

<aside class="item-info-panel">
    <section class="item-info-card">
        <div class="item-info-name-row">
            <span>Nama Quest</span>
            <strong><?= htmlspecialchars($info_quest['quest_name'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>

        <div class="item-info-id-row">
            <span class="item-mini-hash">#</span>
            <span class="item-mini-label">ID</span>
            <span class="item-mini-id"><?= htmlspecialchars($info_quest['quest_id'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>

        <div class="item-info-choice-row">
            <div class="choice-card item-info-choice">
                <span></span>
                <small>Difficulty</small>
                <strong><?= htmlspecialchars($info_quest['quest_difficulty'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="choice-card item-info-choice">
                <span></span>
                <small>Reward</small>
                <strong><?= htmlspecialchars($info_quest['quest_reward'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        </div>   
    </section>

    <section class="item-info-description">
        <h2>Description</h2>
        <p><?= nl2br(htmlspecialchars($info_quest['quest_description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
    </section>

    <h2 class="same-category-title">Quest Dengan difficulty yang Sama</h2>

    <section class="same-category-grid">
        <?php while ($related = mysqli_fetch_assoc($related_query)) : ?>
            <div class="item-mini-card">
                <div class="item-mini-preview"></div>

                <h3 class="item-mini-title">
                    <?= htmlspecialchars($related['quest_name'], ENT_QUOTES, 'UTF-8') ?>
                </h3>

                <div class="item-mini-meta">
                    <span class="item-mini-hash">#</span>
                    <span class="item-mini-label">ID</span>
                    <span class="item-mini-id"><?= htmlspecialchars($related['quest_id'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <div class="item-mini-actions">
                    <a
                        href="quest_page.php?quest_info_id=<?= (int) $related['quest_id'] ?>"
                        class="item-mini-desc"
                    >
                        Description
                    </a>

                    <form method="POST" action="action.php" class="item-mini-delete-form">
                        <input type="hidden" name="quest_id" value="<?= htmlspecialchars($related['quest_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" name="delete_quest" class="item-mini-delete" aria-label="Delete item">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M3 6h18" />
                                <path d="M8 6V4h8v2" />
                                <path d="M6 6l1 15h10l1-15" />
                                <path d="M10 11v6" />
                                <path d="M14 11v6" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </section>

    <div class="item-info-bottom-actions">
        <a href="edit_item.php?item_id=<?= (int) $info_quest['quest_id'] ?>">Edit Quest</a>
        <a href="quest_page.php">Return</a>
    </div>
</aside>