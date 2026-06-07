<?php
$info_item_id = (int) ($_GET['info_item_id'] ?? ($_POST['item_id'] ?? 0));

if (isset($_POST['edit_item_info'])) {
    $edit_item_id = (int) ($_POST['item_id'] ?? 0);
    $item_name = trim($_POST['item_name'] ?? '');
    $item_category = trim($_POST['item_category'] ?? '');
    $item_rarity = trim($_POST['item_rarity'] ?? '');
    $item_description = trim($_POST['item_description'] ?? '');

    if ($edit_item_id > 0 && $item_name !== '' && $item_category !== '' && $item_rarity !== '') {
        $edit_stmt = mysqli_prepare(
            $conn, "UPDATE items SET item_name = ?, item_category = ?, item_rarity = ?, item_description = ? WHERE item_id = ?"
        );

    mysqli_stmt_bind_param($edit_stmt, "ssssi", $item_name, $item_category, $item_rarity, $item_description, $edit_item_id);
    mysqli_stmt_execute($edit_stmt);

    $info_item_id = $edit_item_id;
    }

}

$is_editing_item = isset($_GET['edit_item']);

if ($info_item_id <= 0) {
    return;
}

$info_stmt = mysqli_prepare($conn, "SELECT * FROM items WHERE item_id = ?");
mysqli_stmt_bind_param($info_stmt, "i", $info_item_id);
mysqli_stmt_execute($info_stmt);
$info_item = mysqli_fetch_assoc(mysqli_stmt_get_result($info_stmt));

if (!$info_item) {
    return;
}

$related_stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM items
     WHERE item_category = ?
     ORDER BY item_id DESC
     LIMIT 8"
);



mysqli_stmt_bind_param($related_stmt, "s", $info_item['item_category']);
mysqli_stmt_execute($related_stmt);
$related_query = mysqli_stmt_get_result($related_stmt);

$category_options = mysqli_query(
    $conn, "SELECT DISTINCT item_category FROM items
            WHERE item_category is NOT NULL AND item_category != ''
            ORDER BY item_category ASC"
);

$rarity_options = mysqli_query(
    $conn, "SELECT DISTINCT item_rarity FROM items
            WHERE item_rarity IS NOT NULL AND item_rarity != ''
            ORDER BY item_rarity ASC"
);

?>

<div class="item-info-backdrop" onclick="location.href='item_page.php'"></div>

<aside class="item-info-panel">
        <form
            id="edit-item-info-form"
            method="POST"
            action="item_page.php?info_item_id=<?= (int) $info_item['item_id'] ?>"
        >
            <input type="hidden" name="item_id" value="<?= (int) $info_item['item_id'] ?>">

            <section class="item-info-card">
                <div class="item-info-name-row">
                    <span>Nama Item</span>

                    <?php if ($is_editing_item) : ?>
                        <input
                            class="item-info-input"
                            type="text"
                            name="item_name"
                            value="<?= htmlspecialchars($info_item['item_name'], ENT_QUOTES, 'UTF-8') ?>"
                            required
                        >
                    <?php else : ?>
                        <strong><?= htmlspecialchars($info_item['item_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <?php endif; ?>
                </div>

                <div class="item-info-id-row">
                    <span class="item-mini-hash">#</span>
                    <span class="item-mini-label">ID</span>
                    <span class="item-mini-id"><?= htmlspecialchars($info_item['item_id'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <div class="item-info-choice-row">
                    <div class="choice-card item-info-choice">
                        <span></span>
                        <small>Kategori</small>

                        <?php if ($is_editing_item) : ?>
                            <select class="item-info-select" name="item_category" required>
                                <?php while ($cat = mysqli_fetch_assoc($category_options)) : ?>
                                    <option
                                        value="<?= htmlspecialchars($cat['item_category'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?= $cat['item_category'] === $info_item['item_category'] ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($cat['item_category'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        <?php else : ?>
                            <strong><?= htmlspecialchars($info_item['item_category'], ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php endif; ?>
                    </div>

                    <div class="choice-card item-info-choice">
                        <span></span>
                        <small>Rarity</small>

                        <?php if ($is_editing_item) : ?>
                            <select class="item-info-select" name="item_rarity" required>
                                <?php while ($rarity = mysqli_fetch_assoc($rarity_options)) : ?>
                                    <option
                                        value="<?= htmlspecialchars($rarity['item_rarity'], ENT_QUOTES, 'UTF-8') ?>"
                                        <?= $rarity['item_rarity'] === $info_item['item_rarity'] ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($rarity['item_rarity'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        <?php else : ?>
                            <strong><?= htmlspecialchars($info_item['item_rarity'] ?? 'Common', ENT_QUOTES, 'UTF-8') ?></strong>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </form>
        <section class="item-info-description">
            <h2>Description</h2>

            <?php if ($is_editing_item) : ?>
                <textarea
                    class="item-info-textarea"
                    name="item_description"
                    form="edit-item-info-form"
                ><?= htmlspecialchars($info_item['item_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php else : ?>
                <p><?= nl2br(htmlspecialchars($info_item['item_description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
            <?php endif; ?>
        </section>

    <h2 class="same-category-title">Item Dengan Category yang Sama</h2>

    <section class="same-category-grid">
        <?php while ($related = mysqli_fetch_assoc($related_query)) : ?>
            <div class="item-mini-card">
                <div class="item-mini-preview"></div>
                <h3 class="item-mini-title">
                    <?= htmlspecialchars($related['item_name'], ENT_QUOTES, 'UTF-8') ?>
                </h3>

                <div class="item-mini-meta">
                    <span class="item-mini-hash">#</span>
                    <span class="item-mini-label">ID</span>
                    <span class="item-mini-id"><?= htmlspecialchars($related['item_id'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <div class="item-mini-actions">
                    <a
                        href="item_page.php?info_item_id=<?= (int) $related['item_id'] ?>"
                        class="item-mini-desc"
                    >
                        Description
                    </a>
                    <form method="POST" action="action.php" class="item-mini-delete-form">
                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($related['item_id'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" name="delete_item" class="item-mini-delete" aria-label="Delete item">
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
         <?php if ($is_editing_item) : ?>
            <button type="submit" form="edit-item-info-form" name="edit_item_info">Save</button>
            <a href="item_page.php?info_item_id=<?= (int) $info_item['item_id'] ?>">Cancel</a>
        <?php else : ?>
            <a href="item_page.php?info_item_id=<?= (int) $info_item['item_id'] ?>&edit_item=1">Edit Item</a>
            <a href="item_page.php">Return</a>
        <?php endif; ?>
    </div>
</aside>