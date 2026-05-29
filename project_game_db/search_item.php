<?php
if (!function_exists('e')) {
    function e($value) {
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$search = $search ?? trim($_GET['search'] ?? '');
$category = $category ?? trim($_GET['category'] ?? '');
$rarity = $rarity ?? trim($_GET['rarity'] ?? '');

$category_query = mysqli_query(
    $conn,
    "SELECT DISTINCT item_category 
     FROM items 
     WHERE item_category IS NOT NULL AND item_category != ''
     ORDER BY item_category ASC"
);

$rarity_query = mysqli_query(
    $conn,
    "SELECT DISTINCT item_rarity 
     FROM items 
     WHERE item_rarity IS NOT NULL AND item_rarity != ''
     ORDER BY item_rarity ASC"
);

?>
<div class="search-container">

    <!-- BARIS ATAS -->
    <div class="search-row">

        <form class="search-form" method="GET" action="item_page.php">

            <input
                class="search-box"
                type="text"
                name="search"
                placeholder="Search Item..."
                value="<?= e($search) ?>"
            >

            <button class="search-button" type="submit">
                Search
            </button>

        </form>

        <button type="button" class="add-button" onclick="openAddItemModal()">
            Add Item
        </button>

    </div>

    <!-- KATEGORI -->
    <form method="GET" action="item_page.php">
        Kategori :
        <div class="category-buttons">
            <button
                type="submit"
                name="category"
                value=""
                class="category-btn <?= $category === '' ? 'active' : '' ?>"
            >
                All
            </button>
            <?php while ($cat = mysqli_fetch_assoc($category_query)) : ?>
                <button
                    type="submit"
                    name="category"
                    value="<?= e($cat['item_category']) ?>"
                    class="category-btn <?= $category === $cat['item_category'] ? 'active' : '' ?>"
                >
                    <?= e($cat['item_category']) ?>
                </button>
            <?php endwhile; ?>
        </div>
    </form>

    <form method="GET" action="item_page.php">
    <input type="hidden" name="search" value="<?= e($search) ?>">
    <input type="hidden" name="category" value="<?= e($category) ?>">
        Rarity :
        <div class="category-buttons">
            <button
                type="submit"
                name="rarity"
                value=""
                class="category-btn <?= $rarity === '' ? 'active' : '' ?>"
            >
                All
            </button>
            <?php while ($cat = mysqli_fetch_assoc($rarity_query)) : ?>
                <button
                    type="submit"
                    name="rarity"
                    value="<?= e($cat['item_rarity']) ?>"
                    class="category-btn <?= $rarity === $cat['item_rarity'] ? 'active' : '' ?>"
                >
                    <?= e($cat['item_rarity']) ?>
                </button>
            <?php endwhile; ?>
        </div>
    </form>

</div>