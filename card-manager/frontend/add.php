<?php
require __DIR__ . '/includes/app.php';

$message = null;
$errorMessage = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $type = $_POST['type'] ?? '';

        if ($type === 'player') {
            $username = trim((string) ($_POST['username'] ?? ''));
            $joinDate = trim((string) ($_POST['join_date'] ?? date('Y-m-d')));

            if ($username === '') {
                throw new RuntimeException('Username wajib diisi.');
            }

            execute_query('INSERT INTO player (username, join_date) VALUES (?, ?)', [$username, $joinDate]);
            $message = 'Player berhasil ditambahkan.';
        }

        if ($type === 'item') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $idPlayer = filter_input(INPUT_POST, 'id_player', FILTER_VALIDATE_INT);

            if ($name === '') {
                throw new RuntimeException('Nama item wajib diisi.');
            }

            execute_query(
                'INSERT INTO item (id_player, name, description) VALUES (?, ?, ?)',
                [$idPlayer ?: null, $name, $description ?: null]
            );
            $message = 'Item berhasil ditambahkan.';
        }

        if ($type === 'quest') {
            $title = trim((string) ($_POST['title'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $idPlayer = filter_input(INPUT_POST, 'id_player', FILTER_VALIDATE_INT);

            if ($title === '') {
                throw new RuntimeException('Title quest wajib diisi.');
            }

            execute_query(
                'INSERT INTO quest (id_player, title, description) VALUES (?, ?, ?)',
                [$idPlayer ?: null, $title, $description ?: null]
            );
            $message = 'Quest berhasil ditambahkan.';
        }

        if (!in_array($type, ['player', 'item', 'quest'], true)) {
            throw new RuntimeException('Tipe data tidak dikenal.');
        }
    }
} catch (RuntimeException $error) {
    $errorMessage = $error->getMessage();
} catch (Throwable $error) {
    render_error_page($error);
}

try {
    $players = fetch_all('SELECT * FROM player ORDER BY username ASC');
} catch (Throwable $error) {
    render_error_page($error);
}

page_start('Add Data', 'app-page');
app_header();
?>
  <main class="app-shell">
<?php if ($message): ?>
    <section class="app-message">
      <h1>Berhasil</h1>
      <p><?= e($message) ?></p>
    </section>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <section class="app-message app-message--error">
      <h1>Data belum tersimpan</h1>
      <p><?= e($errorMessage) ?></p>
    </section>
<?php endif; ?>

    <section class="app-form-grid" aria-label="Form tambah data">
      <article class="app-panel">
        <h1>Tambah Player</h1>
        <form class="app-form" method="post">
          <input type="hidden" name="type" value="player" />
          <div class="app-field">
            <label for="username">Username</label>
            <input id="username" name="username" required />
          </div>
          <div class="app-field">
            <label for="join-date">Join Date</label>
            <input id="join-date" name="join_date" type="date" value="<?= e(date('Y-m-d')) ?>" required />
          </div>
          <button class="app-button app-button--primary" type="submit">Simpan Player</button>
        </form>
      </article>

      <article class="app-panel">
        <h1>Tambah Item</h1>
        <form class="app-form" method="post">
          <input type="hidden" name="type" value="item" />
          <div class="app-field">
            <label for="item-player">Player</label>
            <select id="item-player" name="id_player">
              <option value="">Tanpa player</option>
<?php foreach ($players as $player): ?>
              <option value="<?= e($player['id_player']) ?>"><?= e($player['username']) ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <div class="app-field">
            <label for="item-name">Nama Item</label>
            <input id="item-name" name="name" required />
          </div>
          <div class="app-field">
            <label for="item-description">Description</label>
            <textarea id="item-description" name="description"></textarea>
          </div>
          <button class="app-button app-button--primary" type="submit">Simpan Item</button>
        </form>
      </article>

      <article class="app-panel">
        <h1>Tambah Quest</h1>
        <form class="app-form" method="post">
          <input type="hidden" name="type" value="quest" />
          <div class="app-field">
            <label for="quest-player">Player</label>
            <select id="quest-player" name="id_player">
              <option value="">Tanpa player</option>
<?php foreach ($players as $player): ?>
              <option value="<?= e($player['id_player']) ?>"><?= e($player['username']) ?></option>
<?php endforeach; ?>
            </select>
          </div>
          <div class="app-field">
            <label for="quest-title">Title Quest</label>
            <input id="quest-title" name="title" required />
          </div>
          <div class="app-field">
            <label for="quest-description">Description</label>
            <textarea id="quest-description" name="description"></textarea>
          </div>
          <button class="app-button app-button--primary" type="submit">Simpan Quest</button>
        </form>
      </article>
    </section>
  </main>
<?php page_end(); ?>
