<?php
include "config.php"; //

if (isset($_GET['player_id'])) {
    $player_id = (int)$_GET['player_id'];

    // Join completed_quest and quest based on quest_id
    $query = "SELECT quest.quest_name, quest.quest_description, quest.quest_reward, completed_quest.date_completed 
              FROM completed_quest 
              JOIN quest ON completed_quest.quest_id = quest.quest_id 
              WHERE completed_quest.player_id = $player_id";

    $result = mysqli_query($conn, $query);
    $quest_data = [];

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $quest_data[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($quest_data);
}
?>