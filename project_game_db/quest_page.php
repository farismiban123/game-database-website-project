<?php

include "config.php";
$quest_card_query = mysqli_query($conn, "SELECT * FROM quest");

$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $keyword = "%{$search}%";

    $stmt = mysqli_prepare ($conn, "SELECT * FROM quest WHERE 
                                    quest_id LIKE ?
                                    OR quest_name LIKE ?"
    );

    mysqli_stmt_bind_param($stmt, 'ss', $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $quest_card_query = mysqli_stmt_get_result($stmt);
} else {
    $quest_card_query = mysqli_query($conn, "SELECT * FROM quest");
}

$add_difficulty_query = mysqli_query($conn, "SELECT DISTINCT quest_difficulty FROM quest
                                            WHERE quest_difficulty IS NOT NULL AND quest_difficulty != ''
                                            ORDER BY quest_difficulty ASC"
                                            );

#Block untuk filter bedasarkan kategori dan rarity

$search = trim($_GET['search'] ?? '');
$difficulty = trim($_GET['difficulty'] ?? '');

$sql = "SELECT * FROM quest WHERE 1=1";
$params = [];
$types = "";

if ($search !== '') {
    $keyword = "%{$search}%";
    $sql .= " AND (quest_id LIKE ? OR quest_name LIKE ? OR quest_difficulty LIKE ?)";
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
    $types .= "sss";
}

if ($difficulty !== '') {
    $sql .= " AND quest_difficulty = ?";
    $params[] = $difficulty;
    $types .= "s";
}

$sql .= " ORDER BY quest_id ASC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$quest_card_query = mysqli_stmt_get_result($stmt);

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
        </ul>
    </nav>
    <div class="player-container">
        
        <div class="top-bar">
             <?php include "search_quest.php"; ?>
        </div>

        <div class="item-page-grid">

        <?php while ($quest = mysqli_fetch_assoc($quest_card_query)) : ?>

            <div class="item-mini-card">

                <div class="item-mini-preview"></div>

                <h3 class="item-mini-title">
                    <?= htmlspecialchars($quest['quest_name'], ENT_QUOTES, 'UTF-8') ?>
                </h3>

                <div class="item-mini-meta">
                    <span class="item-mini-hash">#</span>
                    <span class="item-mini-label">ID</span>
                    <span class="item-mini-id">
                        <?= htmlspecialchars($quest['quest_id'], ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </div>

                <div class="item-mini-actions">
                    <a
                        href="quest_page.php?quest_info_id=<?= (int) $quest['quest_id'] ?>"
                        class="item-mini-desc"
                    >
                    Description
                    </a>

                    <form method="POST" action="action.php" class="item-mini-delete-form">
                        <input type="hidden" name="quest_id" value="<?= htmlspecialchars($quest['quest_id'], ENT_QUOTES, 'UTF-8') ?>">
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

        </div>
    </div>

    <!--Buat Nambah Halaman-->

    <div class="modal-overlay" id="add-item-modal">
        <div class="add-item-modal">
            <h2>Add New Quest</h2>

            <form method="POST" action="action.php">
                <input type="text" name="quest_name" placeholder="Nama Quest" required>
                <textarea name="quest_description" placeholder="Description"></textarea>
                <input type="text" name="quest_reward" placeholder="Reward" required>

                <div class="choice-section">
                    <p>difficulty</p>

                    <div class="choice-grid" id="category-grid">
                        <?php while ($cat = mysqli_fetch_assoc($add_difficulty_query)) : ?>
                            <label class="choice-card">
                                <input type="radio" name="quest_difficulty" value="<?= htmlspecialchars($cat['quest_difficulty'], ENT_QUOTES, 'UTF-8') ?>" required>
                                <span></span>
                                <small>Difficulty</small>
                                <strong><?= htmlspecialchars($cat['quest_difficulty'], ENT_QUOTES, 'UTF-8') ?></strong>
                            </label>
                        <?php endwhile; ?>

                        <label class="choice-card new-choice-card">
                            <input type="radio" name="quest_difficulty" value="">
                            <span></span>
                            <small>Buat Baru</small>
                            <input type="text" class="inline-new-input" placeholder="..." onkeydown="makeNewChoice(event, this, 'quest_difficulty')">
                        </label>
                    </div>
                </div>
                <div class="modal-buttons">
                    <button type="submit" name="add_quest">Submit</button>
                    <button type="button" onclick="closeAddItemModal()">Return</button>
                </div>
            </form>
        </div>
    </div>
    <?php include "quest_info.php"; ?>
</body>
</html>
<script>
function openAddItemModal() {
    document.getElementById('add-item-modal').style.display = 'flex';
}

function closeAddItemModal() {
    document.getElementById('add-item-modal').style.display = 'none';
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
    card.querySelector('small').innerText = 'Difficulty';

    input.outerHTML = `<strong>${value}</strong>`;
}
</script>
