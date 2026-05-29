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