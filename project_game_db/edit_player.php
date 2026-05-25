<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Player</title>

    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="edit-player-wrapper">
        <h1>Edit Player</h1>
        <div class="edit-player-form-wrapper">
            <div class="edit-player-form">
                <h1>Edit Details</h1>
                <form method="POST" action="action.php">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="number" name="level" placeholder="level" required>
                </form>
            </div>

            <div class="edit-player-form">
                <h1>Edit Inventory</h1>
                <form method="POST" action="action.php">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="number" name="level" placeholder="level" required>
                </form>
            </div>

            <div class="edit-player-form">
                <h1>Edit Quest</h1>
                <form method="POST" action="action.php">
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="number" name="level" placeholder="level" required>
                </form>
            </div>
        </div>

        <div class="button-grid">
            <button type="submit" class="add-player-buttons" name="add">Submit</button>
            <a href="index.php" class="add-player-buttons">Return</a>
        </div>
        
    </div>
</body>
</html>