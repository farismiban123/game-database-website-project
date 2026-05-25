<?php
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$search = $search ?? trim($_GET['search'] ?? '');
?>
<div class ="search-container">
<form class="search-form" method="GET" action="index.php">
    <input
        class="search-box"
        type="text"
        name="search"
        placeholder="Search Player..."
        value="<?= e($search) ?>"
    >
    <button class="search-button" type="submit">Search</button>
</form>
    <a href="add_player.php" class="add-button">Add Player</a>
</div>
