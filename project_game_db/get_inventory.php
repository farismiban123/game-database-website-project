<?php
include "config.php"; // Connect to your database

if (isset($_GET['player_id'])) {
    $player_id = (int)$_GET['player_id'];

    // Join the inventory and items tables based on your database schema
    $query = "SELECT items.item_name, items.item_category 
              FROM inventory 
              JOIN items ON inventory.item_id = items.item_id 
              WHERE inventory.player_id = $player_id";

    $result = mysqli_query($conn, $query);
    $inventory_data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $inventory_data[] = $row;
        }
    }

    // Tell the browser we are sending JSON data, not HTML
    header('Content-Type: application/json');
    echo json_encode($inventory_data);
}
?>