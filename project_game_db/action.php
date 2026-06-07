<?php

include "config.php";

if(isset($_POST['add_player'])){
    $username = $_POST['username'];
    $level = $_POST['level'];

    mysqli_query($conn, "INSERT INTO player (username, player_level)
                         VALUES ('$username', '$level')");

    header("Location: index.php");
    exit;
}

if(isset($_POST['delete_player'])){
    $player_id = (int) $_POST['player_id'];

    mysqli_query($conn, "DELETE FROM inventory WHERE player_id = $player_id");
    mysqli_query($conn, "DELETE FROM player WHERE player_id = $player_id");

    header("Location: index.php");
    exit;
}

if(isset($_POST['add_item'])) {
    $item_name = $_POST['item_name'];
    $item_category = $_POST['item_category'];
    $item_description = $_POST['item_description'];
    $item_rarity = $_POST['item_rarity'];

    mysqli_query($conn, "INSERT INTO items (item_name, item_category, item_description, item_rarity)
                         VALUES ('$item_name', '$item_category', '$item_description', '$item_rarity')");

    header("Location: item_page.php");
    exit;
}
if(isset($_POST['delete_item'])) {
    $item_id = (int) $_POST['item_id'];

    mysqli_query($conn, "DELETE FROM inventory WHERE item_id = $item_id");
    mysqli_query($conn, "DELETE FROM items WHERE item_id = $item_id");

    header("Location: item_page.php");
    exit;
}

if (isset($_POST['add_quest'])) {
    $quest_name = $_POST['quest_name'];
    $quest_description = $_POST['quest_description'];
    $quest_reward = $_POST['quest_reward'];
    $quest_difficulty = $_POST['quest_difficulty'];

    mysqli_query($conn, "INSERT INTO quest (quest_name, quest_description, quest_difficulty, quest_reward)
                         VALUES ('$quest_name', '$quest_description', '$quest_difficulty', '$quest_reward')");

    header("Location: quest_page.php");
    exit;
}

if (isset($_POST['delete_quest'])) {
    $quest_id = (int) $_POST['quest_id'];

    mysqli_query($conn, "DELETE FROM quest WHERE quest_id = $quest_id");

    header("Location: quest_page.php");
    exit;
}

if (isset($_POST['complete_quest'])) {
    $player_id = (int) $_POST['player_id'];
    $quest_id = (int) $_POST['quest_id'];

    mysqli_query($conn, "INSERT INTO completed_quest (player_id, quest_id)
                         VALUES ($player_id, $quest_id)");

    header("Location: index.php");
    exit;
}

if(isset($_POST['edit_player'])) {
    $player_id = (int) $_POST['player_id'];
    $username = $_POST['username'];
    $player_level = (int) $_POST['player_level'];

    mysqli_query($conn, "UPDATE player SET username = '$username',
                            player_level = $player_level
                            WHERE player_id = $player_id");


    header("Location: edit_player.php?player_id=$player_id");
    exit;
}

if (isset($_POST['add_inventory'])) {
    $player_id = (int) $_POST['player_id'];
    $item_id = (int) $_POST['item_id'];

    mysqli_query($conn, "INSERT INTO inventory (player_id, item_id)
                        VALUES ($player_id, $item_id)
    ");

    header("Location: edit_player.php?player_id=$player_id");
    exit;
}

if (isset($_POST['delete_inventory'])) {
    $player_id = (int) $_POST['player_id'];
    $inventory_id = (int) $_POST['inventory_id'];

    mysqli_query($conn, "DELETE FROM inventory WHERE inventory_id = $inventory_id
                        AND player_id = $player_id
    ");

    header("Location: edit_player.php?player_id=$player_id");
    exit;
}

if (isset($_POST['add_completed_quest'])) {
    $player_id = (int) $_POST['player_id'];
    $quest_id = (int) $_POST['quest_id'];

    mysqli_query($conn, "INSERT INTO completed_quest (player_id, quest_id)
                        VALUES ($player_id, $quest_id)
    ");

    header("Location: edit_player.php?player_id=$player_id");
    exit;
}

if (isset($_POST['delete_completed_quest'])) {
    $player_id = (int) $_POST['player_id'];
    $completed_quest_id = (int) $_POST['completed_quest_id'];

    mysqli_query($conn, "DELETE FROM completed_quest WHERE completed_quest_id = $completed_quest_id
                            AND player_id = $player_id
    ");

    header("Location: edit_player.php?player_id=$player_id");
    exit;
}