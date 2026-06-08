<?php
$quest_info_id = (int) ($_GET['quest_info_id'] ?? ($_POST['quest_id'] ?? 0));

if (isset($_POST['edit_quest_info'])) {
    $edit_quest_id = (int) ($_POST['quest_id'] ?? 0);
    $quest_name = trim($_POST['quest_name'] ?? '');
    $quest_description = trim($_POST['quest_description'] ?? '');
    $quest_reward = trim($_POST['quest_reward'] ?? '');
    $quest_difficulty = trim($_POST['quest_difficulty'] ?? '');

    if ($edit_quest_id > 0 && $quest_name !== '' && $quest_reward !== '' && $quest_difficulty !== '') {
        $edit_stmt = mysqli_prepare(
            $conn,
            "UPDATE quest
             SET quest_name = ?, quest_description = ?, quest_reward = ?, quest_difficulty = ?
             WHERE quest_id = ?"
        );

        mysqli_stmt_bind_param(
            $edit_stmt,
            "ssssi",
            $quest_name,
            $quest_description,
            $quest_reward,
            $quest_difficulty,
            $edit_quest_id
        );
        mysqli_stmt_execute($edit_stmt);

        $quest_info_id = $edit_quest_id;
    }
}

$is_editing_quest = isset($_GET['edit_quest']);

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

mysqli_stmt_bind_param($related_stmt, "s", $info_quest['quest_difficulty']);
mysqli_stmt_execute($related_stmt);
$related_query = mysqli_stmt_get_result($related_stmt);

$difficulty_options = mysqli_query(
    $conn,
    "SELECT DISTINCT quest_difficulty FROM quest
     WHERE quest_difficulty IS NOT NULL AND quest_difficulty != ''
     ORDER BY quest_difficulty ASC"
);

?>

<div class="item-info-backdrop" onclick="location.href='quest_page.php'"></div>

<aside class="item-info-panel">
    <form
        id="edit-quest-info-form"
        method="POST"
        action="quest_page.php?quest_info_id=<?= (int) $info_quest['quest_id'] ?>"
    >
        <input type="hidden" name="quest_id" value="<?= (int) $info_quest['quest_id'] ?>">

        <section class="item-info-card">
            <div class="item-info-name-row">
                <span>Nama Quest</span>

                <?php if ($is_editing_quest) : ?>
                    <input
                        class="item-info-input"
                        type="text"
                        name="quest_name"
                        value="<?= htmlspecialchars($info_quest['quest_name'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >
                <?php else : ?>
                    <strong><?= htmlspecialchars($info_quest['quest_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                <?php endif; ?>
            </div>

            <div class="item-info-id-row">
                <span class="item-mini-hash">#</span>
                <span class="item-mini-label">ID</span>
                <span class="item-mini-id"><?= htmlspecialchars($info_quest['quest_id'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>

            <div class="item-info-choice-row">
                <div class="choice-card item-info-choice">
                    <span></span>
                    <small>Reward</small>

                    <?php if ($is_editing_quest) : ?>
                        <input
                            class="item-info-input"
                            type="text"
                            name="quest_reward"
                            value="<?= htmlspecialchars($info_quest['quest_reward'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >
                    <?php else : ?>
                        <strong><?= htmlspecialchars($info_quest['quest_reward'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php endif; ?>
                </div>

                <div class="choice-card item-info-choice">
                    <span></span>
                    <small>Difficulty</small>

                    <?php if ($is_editing_quest) : ?>
                        <select class="item-info-select" name="quest_difficulty" required>
                            <?php while ($difficulty = mysqli_fetch_assoc($difficulty_options)) : ?>
                                <option
                                    value="<?= htmlspecialchars($difficulty['quest_difficulty'], ENT_QUOTES, 'UTF-8') ?>"
                                    <?= $difficulty['quest_difficulty'] === $info_quest['quest_difficulty'] ? 'selected' : '' ?>
                                >
                                    <?= htmlspecialchars($difficulty['quest_difficulty'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    <?php else : ?>
                        <strong><?= htmlspecialchars($info_quest['quest_difficulty'] ?? 'Easy', ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </form>

    <section class="item-info-description">
        <h2>Description</h2>

        <?php if ($is_editing_quest) : ?>
            <textarea
                class="item-info-textarea"
                name="quest_description"
                form="edit-quest-info-form"
            ><?= htmlspecialchars($info_quest['quest_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <?php else : ?>
            <p><?= nl2br(htmlspecialchars($info_quest['quest_description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
        <?php endif; ?>
    </section>

    <h2 class="same-category-title">Quest Dengan Difficulty yang Sama</h2>

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
                        <button type="submit" name="delete_quest" class="item-mini-delete" aria-label="Delete quest">
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
        <?php if ($is_editing_quest) : ?>
            <button type="submit" form="edit-quest-info-form" name="edit_quest_info">Save</button>
            <a href="quest_page.php?quest_info_id=<?= (int) $info_quest['quest_id'] ?>">Cancel</a>
        <?php else : ?>
            <a href="quest_page.php?quest_info_id=<?= (int) $info_quest['quest_id'] ?>&edit_quest=1">Edit Quest</a>
            <a href="quest_page.php">Return</a>
        <?php endif; ?>
    </div>
</aside>