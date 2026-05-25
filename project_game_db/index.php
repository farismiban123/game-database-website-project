<?php

include "config.php";
$player_table_query = mysqli_query($conn, "SELECT * FROM player");
$inventory_table_query = mysqli_query($conn, "SELECT * FROM inventory");

$search = trim($_GET['search'] ?? '');
if ($search !== '') {
    $keyword = "%{$search}%";

    $stmt = mysqli_prepare(
        $conn, "SELECT * FROM player
        WHERE username LIKE ?
            OR player_id LIKE ?"
    );

    mysqli_stmt_bind_param($stmt, 'ss', $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $player_table_query = mysqli_stmt_get_result($stmt);
} else {
    $player_table_query = mysqli_query($conn, "SELECT * FROM player");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Database Project</title>
    <link rel="stylesheet" href="style.css" />

</head>
<body>
    <div class="player-panel" id="side-panel">
        <div class="player-panel-header">
            <h2>Player Details</h2>
        </div>
        <div class="player-panel-card">
            <div class="player-card-left-panel">
                <div class="player-preview"></div>
                <div class="info-box">
                    <span class="label">Level</span>
                    <h1 id="panel-level">12</h1>
                </div>
            </div>
            
            <div class="player-info">
                <div class="info-box">
                    <span class="label">Username</span>
                    <strong id="panel-username">Bob 123</strong>
                </div>

                <div class="info-box">
                    <span class="label">ID</span>
                    <strong id="panel-id">1408</strong>
                </div>

                <div class="info-box">
                    <span class="label">Join Date</span>
                    <strong id="panel-joindate">May 24, 2025</strong>
                </div>
            </div>
        </div>
        <div class="panel-buttons">
            <button onclick="openInventoryGrid()">Inventory</button>
            <button onclick="openQuestGrid()">Quest</button>
        </div>
        <div class="inventory-grid" id="panel-inventory-grid">

        </div>

        <div class="quest-grid" id="panel-quest-grid">
           

        </div>

        <div class="panel-buttons">
            <a href="#" id="panel-edit-button">Edit Player</a>
            <button onclick="closePlayerPanel()">Close Panel</button>
        </div>
    </div>

    <nav>
        <ul>
            <li><a href="#">Player List</a></li>
            <li><a href="#">Quest List</a></li>
            <li><a href="items.php">Items List</a></li>
            <li><a href="#">Modifier List</a></li>
        </ul>
    </nav>
    <div class="player-container">

        <div class="top-bar">
             <?php include "search.php"; ?>
        </div>

        <div class="player-card-grid">

        <?php

        while ($player = mysqli_fetch_assoc($player_table_query)) : ?>

            <div class="player-card">

                <div class="player-card-left-panel">
                    <div class="player-preview"></div>
                    <div class="info-box">
                        <span class="label">Level</span>
                        <h1><?= $player['player_level'] ?></h1>
                    </div>
                </div>

                <div class="player-info">
                    <div class="info-box">
                        <span class="label">Username</span>
                        <strong><?= $player['username'] ?></strong>
                    </div>

                    <div class="info-box">
                        <span class="label">ID</span>
                        <strong><?= $player['player_id'] ?></strong>
                    </div>

                    <div class="info-box">
                        <span class="label">Join Date</span>
                        <strong><?= $player['date_joined'] ?></strong>
                    </div>
                </div>

                <div class="card-buttons">
                    <button class="btn-info" 
                        data-id="<?= $player['player_id'] ?>" 
                        data-username="<?= $player['username'] ?>" 
                        data-joindate="<?= $player['date_joined'] ?>" 
                        data-level="<?= $player['player_level'] ?>" 
                        onclick="openPlayerPanel(this)">
                        Info Player
                    </button>
                    <form method="POST" action="action.php" onsubmit="return confrim('Yakin mau hapus player ini');">
                        <input type="hidden" name="player_id" value="<?= $player['player_id'] ?>">
                        <button type="submit" class="btn-delete" name="delete_player">Delete Player</button>
                    </form>
                </div>

            </div>

            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>

<script>
    function openPlayerPanel(buttonElement) {
        // 1. Grab the basic player data from the button's data-* attributes
        const playerId = buttonElement.getAttribute('data-id');
        const playerUsername = buttonElement.getAttribute('data-username');
        const playerJoinDate = buttonElement.getAttribute('data-joindate');
        const playerLevel = buttonElement.getAttribute('data-level');

        // 2. Set the basic text in the panel
        document.getElementById('panel-username').innerText = playerUsername;
        document.getElementById('panel-id').innerText = playerId;
        document.getElementById('panel-joindate').innerText = playerJoinDate;
        document.getElementById('panel-level').innerText = playerLevel;
        document.getElementById('panel-edit-button').href = `edit_player.php?player_id=${playerId}`;

        // 3. Open the panel immediately so the user sees something happening
        document.getElementById('side-panel').style.display = 'flex';

        // 4. Start the AJAX Fetch for the inventory
        const inventoryGrid = document.getElementById('panel-inventory-grid');
        inventoryGrid.innerHTML = '<p style="text-align:center; padding: 20px;">Loading inventory...</p>'; 

        // Call our new PHP file and pass the player ID in the URL
        fetch('get_inventory.php?player_id=' + playerId)
            .then(response => response.json()) // Convert the response to a JavaScript array
            .then(data => {
                // Clear out the "Loading..." text
                inventoryGrid.innerHTML = ''; 

                // Check if the player has no items
                if (data.length === 0) {
                    inventoryGrid.innerHTML = '<p style="text-align:center;">Inventory is empty.</p>';
                    return; // Stop running the function
                }

                // Loop through the data array and build an HTML card for each item
                data.forEach(item => {
                    const itemCard = `
                        <div class="item-card">
                            <h2>${item.item_name}</h2>
                            <div class="item-preview"></div>
                            <div class="player-info">   
                                <div class="info-box">
                                    <span class="label">Category</span>
                                    <strong>${item.item_category}</strong>
                                </div>
                                <div class="info-box">
                                    <span class="label">Modifier ID</span>
                                    <strong>${item.modifier_id || 'None'}</strong>
                                </div>
                            </div>
                            <button>Item Description</button>
                        </div>
                    `;
                    // Inject the finished HTML string into the grid
                    inventoryGrid.innerHTML += itemCard;
                });
            })
            .catch(error => {
                console.error("AJAX Error:", error);
                inventoryGrid.innerHTML = '<p>Error loading items.</p>';
            });
        
        const questGrid = document.getElementById('panel-quest-grid');
        questGrid.innerHTML = '<p style="text-align:center; padding: 20px;">Loading quests...</p>'; 

        fetch('get_quests.php?player_id=' + playerId)
            .then(response => response.json())
            .then(data => {
                questGrid.innerHTML = ''; 

                if (data.length === 0) {
                    questGrid.innerHTML = '<p style="text-align:center;">No completed quests.</p>';
                    return; 
                }

                data.forEach(quest => {
                    const questCard = `
                        <div class="quest-card">
                            <h2>${quest.quest_name}</h2>
                            <div class="quest-preview"></div>
                            
                            <div class="player-info">   
                                <div class="info-box">
                                    <span class="label">Reward</span>
                                    <strong>${quest.quest_reward}</strong>
                                </div>
                                <div class="info-box">
                                    <span class="label">Date Completed</span>
                                    <strong>${quest.date_completed}</strong>
                                </div>
                            </div>
                            <button>Quest Description</button>
                        </div>
                    `;
                    questGrid.innerHTML += questCard;
                });
            })
            .catch(error => {
                console.error("AJAX Error:", error);
                questGrid.innerHTML = '<p>Error loading quests.</p>';
            });
    }

    function closePlayerPanel() {
        document.getElementById('side-panel').style.display = 'none';
    }

    function openInventoryGrid(){
        document.getElementById('panel-inventory-grid').style.display = 'grid';
        document.getElementById('panel-quest-grid').style.display = 'none';
    }

    function openQuestGrid(){
        document.getElementById('panel-quest-grid').style.display = 'grid';
        document.getElementById('panel-inventory-grid').style.display = 'none';
    }
</script>

<button class="btn-delete">Delete Player</button>