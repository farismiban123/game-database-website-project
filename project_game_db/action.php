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

    mysqli_query($conn, "INSERT INTO items (item_name, item_category, item_description)
                         VALUES ('$item_name', '$item_category', '$item_description')");

    header("Location: ")
    exit;
}
