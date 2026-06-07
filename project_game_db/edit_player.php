<?php
include "config.php";

$player_id = (int) ($_GET['player_id'] ?? 0);

if ($player_id <= 0) {
    header("Location: index.php");
    exit;
}

$player_query = mysqli_query($conn, "SELECT * FROM player WHERE player_id = $player_id");
$player = mysqli_fetch_assoc($player_query);

if (!$player) {
    header("Location: index.php");
    exit;
}

$items_query = mysqli_query($conn, "SELECT item_id, item_name FROM items ORDER BY item_name ASC");
$quests_query = mysqli_query($conn, "SELECT quest_id, quest_name FROM quest ORDER BY quest_name ASC");

$inventory_query = mysqli_query($conn, "SELECT inventory.inventory_id, inventory.item_id, items.item_name
                                        FROM inventory
                                        JOIN items ON inventory.item_id = items.item_id
                                        WHERE inventory.player_id = $player_id
");

$completed_quest_query = mysqli_query($conn, "SELECT completed_quest.completed_quest_id, completed_quest.quest_id, quest.quest_name
                                                FROM completed_quest
                                                JOIN quest ON completed_quest.quest_id = quest.quest_id
                                                WHERE completed_quest.player_id = $player_id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player</title>

    <link rel="stylesheet" href="style1.css">
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<div class="edit-page-wrapper">
    <main class="edit-page">

        <section class="edit-column">
            <div class="edit-panel">
                <h2>Edit Nama Player</h2>

                <form method="POST" action="action.php" class="edit-form">
                    <input type="hidden" name="player_id" value="<?= (int) $player['player_id'] ?>">

                    <input
                        type="text"
                        name="username"
                        placeholder="Nama Player"
                        value="<?= htmlspecialchars($player['username'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <input
                        type="number"
                        name="player_level"
                        placeholder="Level"
                        value="<?= htmlspecialchars($player['player_level'], ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                    <div class="edit-button-row">
                        <button type="submit" name="edit_player">Submit</button>
                        <a href="index.php">Return</a>
                    </div>
                </form>
            </div>

            <div class="edit-empty-panel"></div>
        </section>

        <section class="edit-main-panel">
            <div class="edit-sub-panel">
                <h2>Edit Inventory</h2>

                <form method="POST" action="action.php" class="edit-form">
                    <input type="hidden" name="player_id" value="<?= (int) $player['player_id'] ?>">

                    <select name="item_id" required>
                        <option value="">Add Item</option>
                        <?php while ($item = mysqli_fetch_assoc($items_query)) : ?>
                            <option value="<?= (int) $item['item_id'] ?>">
                                <?= htmlspecialchars($item['item_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <input type="number" name="modifier_id" placeholder="Modifier ID (Optional)">

                    <div class="edit-button-row">
                        <button type="submit" name="add_inventory">Submit</button>
                        <a href="index.php">Return</a>
                    </div>
                </form>

                <h2>Current Inventory</h2>

                <div class="edit-card-list">
                    <?php while ($inventory = mysqli_fetch_assoc($inventory_query)) : ?>
                        <div class="item-mini-card">
                            <div class="item-mini-preview"></div>

                            <h3 class="item-mini-title">
                                <?= htmlspecialchars($inventory['item_name'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>

                            <div class="item-mini-meta">
                                <span class="item-mini-hash">#</span>
                                <span class="item-mini-label">ID</span>
                                <span class="item-mini-id"><?= (int) $inventory['item_id'] ?></span>
                            </div>

                            <div class="item-mini-actions">
                                <a href="item_page.php?info_item_id=<?= (int) $inventory['item_id'] ?>" class="item-mini-desc">
                                    Description
                                </a>
                                <form method="POST" action="action.php" class="item-mini-delete-form">
                                    <input type="hidden" name="player_id" value="<?= (int) $player_id ?>">
                                    <input type="hidden" name="inventory_id" value="<?= (int) $inventory['inventory_id'] ?>">
                                    <button type="submit" name="delete_inventory" class="item-mini-delete">
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

            <div class="edit-sub-panel">
                <h2>Edit Quest</h2>

                <form method="POST" action="action.php" class="edit-form">
                    <input type="hidden" name="player_id" value="<?= (int) $player['player_id'] ?>">

                    <select name="quest_id" required>
                        <option value="">Add Quest</option>
                        <?php while ($quest = mysqli_fetch_assoc($quests_query)) : ?>
                            <option value="<?= (int) $quest['quest_id'] ?>">
                                <?= htmlspecialchars($quest['quest_name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <div class="edit-button-row">
                        <button type="submit" name="add_completed_quest">Submit</button>
                        <a href="index.php">Return</a>
                    </div>
                </form>

                <h2>Completed Quest</h2>

                <div class="edit-card-list">
                    <?php while ($completed = mysqli_fetch_assoc($completed_quest_query)) : ?>
                        <div class="item-mini-card">
                            <div class="item-mini-preview"></div>

                            <h3 class="item-mini-title">
                                <?= htmlspecialchars($completed['quest_name'], ENT_QUOTES, 'UTF-8') ?>
                            </h3>

                            <div class="item-mini-meta">
                                <span class="item-mini-hash">#</span>
                                <span class="item-mini-label">ID</span>
                                <span class="item-mini-id"><?= (int) $completed['quest_id'] ?></span>
                            </div>

                            <div class="item-mini-actions">
                                <a href="quest_page.php?quest_info_id=<?= (int) $completed['quest_id'] ?>" class="item-mini-desc">
                                    Description
                                </a>

                                <form method="POST" action="action.php" class="item-mini-delete-form">
                                    <input type="hidden" name="player_id" value="<?= (int) $player_id ?>">
                                    <input type="hidden" name="completed_quest_id" value="<?= (int) $completed['completed_quest_id'] ?>">
                                    <button type="submit" name="delete_completed_quest" class="item-mini-delete">
                                    <svg viewBox="0 0 24 24" fill="none">
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4h8v2" />
                                        <path d="M6 6l1 15h10l1-15" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                    </svg>
                                </button>
                                    </button>
                                    
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>

    </main>
</div>

</body>
</html>