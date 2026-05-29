<?php
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$search = $search ?? trim($_GET['search'] ?? '');
$difficulty = $difficulty ?? trim($_GET['difficulty'] ?? '');

$difficulty_query = mysqli_query(
    $conn,
    "SELECT DISTINCT quest_difficulty 
     FROM quest 
     WHERE quest_difficulty IS NOT NULL AND quest_difficulty != ''
     ORDER BY quest_difficulty ASC"
);


?>
<div class="search-container">

    <!-- BARIS ATAS -->
    <div class="search-row">

        <form class="search-form" method="GET" action="quest_page.php">

            <input
                class="search-box"
                type="text"
                name="search"
                placeholder="Search Quest..."
                value="<?= e($search) ?>"
            >

            <button class="search-button" type="submit">
                Search
            </button>

        </form>

        <button type="button" class="add-button" onclick="openAddItemModal()">
            Add Quest
        </button>

    </div>

    <!-- KATEGORI -->
    <form method="GET" action="quest_page.php">
    <input type="hidden" name="search" value="<?= e($search) ?>">
    <input type="hidden" name="difficulty" value="<?= e($difficulty) ?>">
        Difficulty :
        <div class="category-buttons">
            <button
                type="submit"
                name="difficulty"
                value=""
                class="category-btn <?= $difficulty === '' ? 'active' : '' ?>"
            >
                All
            </button>
            <?php while ($cat = mysqli_fetch_assoc($difficulty_query)) : ?>
                <button
                    type="submit"
                    name="difficulty"
                    value="<?= e($cat['quest_difficulty']) ?>"
                    class="category-btn <?= $difficulty === $cat['quest_difficulty'] ? 'active' : '' ?>"
                >
                    <?= e($cat['quest_difficulty']) ?>
                </button>
            <?php endwhile; ?>
        </div>
    </form>
</div>