<?php
$info_item_id = (int) ($_GET['info_item_id'] ?? 0);

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

?>

<div class="item-info-backdrop" onclick="location.href='item_page.php'"></div>

<aside class="item-info-panel">
    <section class="item-info-card">
        <div class="item-info-name-row">
            <span>Nama Item</span>
            <strong><?= htmlspecialchars($info_item['item_name'], ENT_QUOTES, 'UTF-8') ?></strong>
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
                <strong><?= htmlspecialchars($info_item['item_category'], ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="choice-card item-info-choice">
                <span></span>
                <small>Rarity</small>
                <strong><?= htmlspecialchars($info_item['item_rarity'] ?? 'Common', ENT_QUOTES, 'UTF-8') ?></strong>
            </div>
        </div>
    </section>

    <section class="item-info-description">
        <h2>Description</h2>
        <p><?= nl2br(htmlspecialchars($info_item['item_description'] ?? '', ENT_QUOTES, 'UTF-8')) ?></p>
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
        <a href="edit_item.php?item_id=<?= (int) $info_item['item_id'] ?>">Edit Item</a>
        <a href="item_page.php">Return</a>
    </div>
</aside>