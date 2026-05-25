<?php

include "config.php";
$player_id = (int)$_GET['player_id'];
$query = mysqli_query($conn, "SELECT * FROM player WHERE player_id=$player_id");
$player = mysqli_fetch_assoc($query);

$items_query = mysqli_query($conn, "SELECT * FROM items");
$quests_query = mysqli_query($conn, "SELECT * FROM quest");

$inventory_query = mysqli_query($conn, "SELECT items.item_id, items.item_name, items.item_category, inventory.modifier_id 
                                        FROM inventory 
                                        JOIN items ON inventory.item_id = items.item_id 
                                        WHERE inventory.player_id = $player_id");

$completed_quest_query = mysqli_query($conn, "SELECT quest.quest_id, quest.quest_name, quest.quest_description, quest.quest_reward, completed_quest.date_completed 
                                              FROM completed_quest 
                                              JOIN quest ON completed_quest.quest_id = quest.quest_id 
                                              WHERE completed_quest.player_id = $player_id");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player</title>

    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="edit-player-wrapper">
        <h1>Edit Player: <?= $player['username'] ?></h1>
        <div class="edit-player-form-wrapper">
            <div class="edit-player-form">
                <h1>Edit Details</h1>
                <form method="POST" action="action.php?player_id=<?=$player_id ?>">
                    <input type="text" name="username" placeholder="Name" value="<?= $player['username'] ?>" required>
                    <input type="number" name="level" placeholder="level" value="<?= $player['player_level'] ?>" required>

                    <div class="button-grid">
                        <button type="submit" class="add-player-buttons" name="update">Update</button>
                        <a href="index.php" class="add-player-buttons">Return</a>
                    </div>
                </form>
            </div>

            <div class="edit-player-form">
                <h1>Edit Inventory</h1>
                <h2>Current Inventory</h2>
                <div class="edit-form-player-quest-grid" style="max-width: 800px; margin: 0 auto; gap: 20px;">
                    <?php 
                    // Check if they have items
                    if(mysqli_num_rows($inventory_query) > 0) : 
                        while($item = mysqli_fetch_assoc($inventory_query)) : 
                    ?>
                        <div class="item-card">
                            <h2><?= $item['item_name'] ?></h2>
                            <div class="item-preview"></div>
                            
                            <div class="player-info">   
                                <div class="info-box">
                                    <span class="label">Category</span>
                                    <strong><?= $item['item_category'] ?></strong>
                                </div>
                                <div class="info-box">
                                    <span class="label">Modifier ID</span>
                                    <strong><?= $item['modifier_id'] ? $item['modifier_id'] : 'None' ?></strong>
                                </div>
                            </div>


                            <form method="POST" action="action.php?player_id=<?= $player_id ?>">
                                <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                                <input type="hidden" name="modifier_id" value="<?= $item['modifier_id'] ?>">
                                
                                <button type="submit" name="delete_item" class="btn-delete">Remove Item</button>
                            </form>
                        </div>
                    <?php 
                        endwhile;
                    else : 
                    ?>
                        <p style="text-align: center; grid-column: 1 / -1;">This player's inventory is empty.</p>
                    <?php endif; ?>
                </div>
                <form method="POST" action="action.php?player_id=<?=$player_id ?>">
                    <select name="item_id" required>
                        <option value="" disabled selected>Select an Item</option>
                        <?php while($item = mysqli_fetch_assoc($items_query)) : ?>
                            <option value="<?= $item['item_id'] ?>"><?= $item['item_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <input type="number" name="modifier_id" placeholder="Modifier ID (Optional)">

                    <div class="button-grid">
                        <button type="submit" class="add-player-buttons" name="add_inventory">Give Item</button>
                    </div>
                </form>
            </div>

            <div class="edit-player-form">
                <h1>Edit Completed Quests</h1>
                <h2>Current Completed Quests</h2>
                <div class="edit-form-player-quest-grid" style="max-width: 800px; margin: 0 auto; gap: 20px;">
                    <?php 
                    // Check if they have items
                    if(mysqli_num_rows($completed_quest_query) > 0) : 
                        while($quest = mysqli_fetch_assoc($completed_quest_query)) : 
                    ?>
                        <div class="quest-card">
                            <h2><?= $quest['quest_name'] ?></h2>
                            <div class="quest-preview"></div>
                            
                            <div class="player-info">   
                                <div class="info-box">
                                    <span class="label">Reward</span>
                                    <strong><?= $quest['quest_reward'] ?></strong>
                                </div>
                                <div class="info-box">
                                    <span class="label">Date Completed</span>
                                    <strong><?= $quest['date_completed'] ?></strong>
                                </div>
                            </div>


                            <form method="POST" action="action.php?player_id=<?= $player_id ?>">
                                <input type="hidden" name="quest_id" value="<?= $quest['quest_id'] ?>">
                                
                                <button type="submit" name="delete_quest" class="btn-delete">Remove Quest</button>
                            </form>
                        </div>
                    <?php 
                        endwhile;
                    else : 
                    ?>
                        <p style="text-align: center; grid-column: 1 / -1;">This player has not completed any quests.</p>
                    <?php endif; ?>
                </div>
                <form method="POST" action="action.php?player_id=<?=$player_id ?>">
                    <select name="quest_id" required>
                        <option value="" disabled selected>Select a quest</option>
                        <?php while($quest = mysqli_fetch_assoc($quests_query)) : ?>
                            <option value="<?= $quest['quest_id'] ?>"><?= $quest['quest_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                    

                    <div class="button-grid">
                        <button type="submit" class="add-player-buttons" name="add_quest">Give Quest</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>