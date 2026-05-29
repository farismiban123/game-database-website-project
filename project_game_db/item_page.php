<?php

include "config.php";
$item_card_query = mysqli_query($conn, "SELECT * FROM items");

$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $keyword = "%{$search}%";

    $stmt = mysqli_prepare ($conn, "SELECT * FROM items WHERE 
                                    item_id LIKE ?
                                    OR item_name LIKE ?"
    );

    mysqli_stmt_bind_param($stmt, 'ss', $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $item_card_query = mysqli_stmt_get_result($stmt);
} else {
    $item_card_query = mysqli_query($conn, "SELECT * FROM items");
}

$add_category_query = mysqli_query($conn, "SELECT DISTINCT item_category FROM items
                                            WHERE item_category IS NOT NULL AND item_category != ''
                                            ORDER BY item_category ASC"
                                            );


#Block untuk filter bedasarkan kategori dan rarity

$search = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');
$rarity = trim($_GET['rarity'] ?? '');

$sql = "SELECT * FROM items WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $keyword = "%{$search}%";
    $sql .= " AND (item_id LIKE ? OR item_name LIKE ? OR item_category LIKE ? OR item_rarity LIKE ?)";
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $types .= "ssss";
}

if ($category !== '') {
    $sql .= " AND item_category = ?";
    $params[] = $category;
    $types .= "s";
}

if ($rarity !== '') {
    $sql .= " AND item_rarity = ?";
    $params[] = $rarity;
    $types .= "s";
}

$sql .= " ORDER BY item_id ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$item_card_query = mysqli_stmt_get_result($stmt);

$add_rarity_query = mysqli_query(
    $conn,
    "SELECT DISTINCT item_rarity FROM items
     WHERE item_rarity IS NOT NULL AND item_rarity != ''
     ORDER BY item_rarity ASC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Database Project</title>
    <link rel="stylesheet" href="style1.css" />
    <link rel="stylesheet" href="item_info.css" />

</head>
<body>
    <nav>
        <ul>
            <li><a href="index.php">Player List</a></li>
            <li><a href="quest_page.php">Quest List</a></li>
            <li><a href="item_page.php">Items List</a></li>
            <li><a href="#">Modifier List</a></li>
        </ul>
    </nav>
    <div class="player-container">
        
        <div class="top-bar">
             <?php include "search_item.php"; ?>
        </div>

        <div class="item-page-grid">

        <?php while ($item = mysqli_fetch_assoc($item_card_query)) : ?>

            <div class="item-mini-card">

                <div class="item-mini-preview"></div>

                <h3 class="item-mini-title">
                    <?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8') ?>
                </h3>

                <div class="item-mini-meta">
                    <span class="item-mini-hash">#</span>
                    <span class="item-mini-label">ID</span>
                    <span class="item-mini-id">
                        <?= htmlspecialchars($item['item_id'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>

                <div class="item-mini-actions">
                    <a
                        href="item_page.php?info_item_id=<?= (int) $item['item_id'] ?>"
                        class="item-mini-desc"
                    >
                    Description
                    </a>

                    <form method="POST" action="action.php" class="item-mini-delete-form">
                        <input type="hidden" name="item_id" value="<?= htmlspecialchars($item['item_id'], ENT_QUOTES, 'UTF-8') ?>">
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

        </div>
    </div>

    <!--Buat Nambah Halaman-->

    <div class="modal-overlay" id="add-quest-modal">
        <div class="add-item-modal">
            <h2>Add New Item</h2>

            <form method="POST" action="action.php">
                <input type="text" name="item_name" placeholder="Nama Item" required>

                <textarea name="item_description" placeholder="Description"></textarea>

                <div class="choice-section">
                    <p>Kategori</p>

                    <div class="choice-grid" id="category-grid">
                        <?php while ($cat = mysqli_fetch_assoc($add_category_query)) : ?>
                            <label class="choice-card">
                                <input type="radio" name="item_category" value="<?= htmlspecialchars($cat['item_category'], ENT_QUOTES, 'UTF-8') ?>" required>
                                <span></span>
                                <small>Kategori</small>
                                <strong><?= htmlspecialchars($cat['item_category'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </label>
                        <?php endwhile; ?>

                        <label class="choice-card new-choice-card">
                            <input type="radio" name="item_category" value="">
                            <span></span>
                            <small>Buat Baru</small>
                            <input type="text" class="inline-new-input" placeholder="..." onkeydown="makeNewChoice(event, this, 'item_category')">
                        </label>
                    </div>

                    <p>Rarity</p>

                    <div class="choice-grid" id="rarity-grid">
                        <?php while ($rarity = mysqli_fetch_assoc($add_rarity_query)) : ?>
                            <label class="choice-card">
                                <input type="radio" name="item_rarity" value="<?= htmlspecialchars($rarity['item_rarity'], ENT_QUOTES, 'UTF-8') ?>" required>
                                <span></span>
                                <small>Rarity</small>
                                <strong><?= htmlspecialchars($rarity['item_rarity'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </label>
                        <?php endwhile; ?>

                        <label class="choice-card new-choice-card">
                            <input type="radio" name="item_rarity" value="">
                            <span></span>
                            <small>Buat Baru</small>
                            <input type="text" class="inline-new-input" placeholder="..." onkeydown="makeNewChoice(event, this, 'item_rarity')">
                        </label>
                    </div>
                </div>

                <div class="modal-buttons">
                    <button type="submit" name="add_item">Submit</button>
                    <button type="button" onclick="closeAddItemModal()">Return</button>
                </div>
            </form>
        </div>
    </div>
    <?php include "item_info.php"; ?>
</body>
</html>
<script>
function openAddItemModal() {
    document.getElementById('add-quest-modal').style.display = 'flex';
}

function closeAddItemModal() {
    document.getElementById('add-quest-modal').style.display = 'none';
}

function makeNewChoice(event, input, fieldName) {
    if (event.key !== 'Enter') return;

    event.preventDefault();

    const value = input.value.trim();
    if (value === '') return;

    const card = input.closest('.choice-card');
    const radio = card.querySelector('input[type="radio"]');

    radio.value = value;
    radio.checked = true;

    card.classList.remove('new-choice-card');
    card.classList.add('created-choice');
    card.querySelector('small').innerText = fieldName === 'item_category' ? 'Kategori' : 'Rarity';

    input.outerHTML = `<strong>${value}</strong>`;
}
</script>