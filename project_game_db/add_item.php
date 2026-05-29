<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>

    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="add-player-wrapper">
        <div class="add-player-form">
            <h1>Edit Item</h1>
            <form method="POST" action="action.php">
                <input type="text" name="username" placeholder="username" required>
                <input type="number" name="level" placeholder="level" required>
            
                <div class="button-grid">
                    <button type="submit" class="add-player-buttons" name="add_player">Submit</button>
                    <a href="index.php" class="add-player-buttons">Return</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>