<?php

declare(strict_types=1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function json_response($data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function database(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = require __DIR__ . '/config.php';
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['database']
    );

    try {
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $error) {
        json_response([
            'message' => 'Tidak bisa terhubung ke database. Pastikan MySQL XAMPP aktif dan database card_manager sudah dibuat.',
            'detail' => $error->getMessage(),
        ], 500);
    }

    return $pdo;
}

function route_segments(): array
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

    $indexPosition = strpos($path, '/index.php');
    if ($indexPosition !== false) {
        $path = substr($path, $indexPosition + strlen('/index.php'));
    } else {
        $apiPosition = strpos($path, '/backend/api');
        if ($apiPosition !== false) {
            $path = substr($path, $apiPosition + strlen('/backend/api'));
        }
    }

    $segments = array_values(array_filter(explode('/', trim($path, '/')), 'strlen'));

    if (($segments[0] ?? '') === 'index.php') {
        array_shift($segments);
    }

    if (($segments[0] ?? '') === 'api') {
        array_shift($segments);
    }

    return $segments;
}

function read_json_body(): array
{
    $rawBody = file_get_contents('php://input');

    if ($rawBody === false || trim($rawBody) === '') {
        return [];
    }

    $data = json_decode($rawBody, true);

    if (!is_array($data)) {
        json_response(['message' => 'Body JSON tidak valid'], 400);
    }

    return $data;
}

function normalize_id($value): ?int
{
    if ($value === null || $value === '') {
        return null;
    }

    $id = filter_var($value, FILTER_VALIDATE_INT);
    return $id === false ? null : $id;
}

function run_query(string $sql, array $params = []): PDOStatement
{
    $statement = database()->prepare($sql);
    $statement->execute($params);
    return $statement;
}

function handle_database_error(PDOException $error): void
{
    if ($error->getCode() === '23000') {
        json_response(['message' => 'Data relasi tidak valid. Pastikan id_player tersedia di tabel player.'], 400);
    }

    json_response([
        'message' => 'Terjadi kesalahan pada database',
        'detail' => $error->getMessage(),
    ], 500);
}

function handle_players(string $method, ?string $id): void
{
    if ($method === 'GET' && $id === null) {
        $players = run_query('SELECT * FROM player ORDER BY id_player DESC')->fetchAll();
        json_response($players);
    }

    if ($method === 'GET') {
        $idPlayer = normalize_id($id);

        if (!$idPlayer) {
            json_response(['message' => 'id_player tidak valid'], 400);
        }

        $player = run_query('SELECT * FROM player WHERE id_player = ?', [$idPlayer])->fetch();

        if (!$player) {
            json_response(['message' => 'Player tidak ditemukan'], 404);
        }

        $items = run_query('SELECT * FROM item WHERE id_player = ? ORDER BY id_item ASC', [$idPlayer])->fetchAll();
        $quests = run_query('SELECT * FROM quest WHERE id_player = ? ORDER BY id_quest ASC', [$idPlayer])->fetchAll();

        json_response(array_merge($player, [
            'items' => $items,
            'quests' => $quests,
        ]));
    }

    if ($method === 'POST') {
        $data = read_json_body();
        $username = trim((string) ($data['username'] ?? ''));
        $joinDate = trim((string) ($data['join_date'] ?? date('Y-m-d')));

        if ($username === '') {
            json_response(['message' => 'username wajib diisi'], 400);
        }

        run_query('INSERT INTO player (username, join_date) VALUES (?, ?)', [$username, $joinDate]);
        $newId = (int) database()->lastInsertId();
        $player = run_query('SELECT * FROM player WHERE id_player = ?', [$newId])->fetch();

        json_response($player, 201);
    }

    json_response(['message' => 'Method tidak didukung'], 405);
}

function handle_items(string $method): void
{
    if ($method === 'GET') {
        $idPlayer = normalize_id($_GET['id_player'] ?? null);
        $params = [];
        $whereClause = '';

        if ($idPlayer) {
            $whereClause = 'WHERE item.id_player = ?';
            $params[] = $idPlayer;
        }

        $items = run_query("
            SELECT item.*, player.username
            FROM item
            LEFT JOIN player ON player.id_player = item.id_player
            $whereClause
            ORDER BY item.id_item DESC
        ", $params)->fetchAll();

        json_response($items);
    }

    if ($method === 'POST') {
        $data = read_json_body();
        $name = trim((string) ($data['name'] ?? ''));
        $description = $data['description'] ?? null;
        $idPlayer = normalize_id($data['id_player'] ?? null);

        if ($name === '') {
            json_response(['message' => 'name wajib diisi'], 400);
        }

        run_query(
            'INSERT INTO item (id_player, name, description) VALUES (?, ?, ?)',
            [$idPlayer, $name, $description]
        );

        $newId = (int) database()->lastInsertId();
        $item = run_query('SELECT * FROM item WHERE id_item = ?', [$newId])->fetch();

        json_response($item, 201);
    }

    json_response(['message' => 'Method tidak didukung'], 405);
}

function handle_quests(string $method): void
{
    if ($method === 'GET') {
        $idPlayer = normalize_id($_GET['id_player'] ?? null);
        $params = [];
        $whereClause = '';

        if ($idPlayer) {
            $whereClause = 'WHERE quest.id_player = ?';
            $params[] = $idPlayer;
        }

        $quests = run_query("
            SELECT quest.*, player.username
            FROM quest
            LEFT JOIN player ON player.id_player = quest.id_player
            $whereClause
            ORDER BY quest.id_quest DESC
        ", $params)->fetchAll();

        json_response($quests);
    }

    if ($method === 'POST') {
        $data = read_json_body();
        $title = trim((string) ($data['title'] ?? ''));
        $description = $data['description'] ?? null;
        $idPlayer = normalize_id($data['id_player'] ?? null);

        if ($title === '') {
            json_response(['message' => 'title wajib diisi'], 400);
        }

        run_query(
            'INSERT INTO quest (id_player, title, description) VALUES (?, ?, ?)',
            [$idPlayer, $title, $description]
        );

        $newId = (int) database()->lastInsertId();
        $quest = run_query('SELECT * FROM quest WHERE id_quest = ?', [$newId])->fetch();

        json_response($quest, 201);
    }

    json_response(['message' => 'Method tidak didukung'], 405);
}

function item_to_card(array $item): array
{
    return [
        'id' => (string) $item['id_item'],
        'name' => $item['name'],
        'description' => $item['description'],
        'image' => null,
        'createdAt' => date('c'),
    ];
}

function handle_cards(string $method, ?string $id): void
{
    if ($method === 'GET' && $id === null) {
        $items = run_query('SELECT * FROM item ORDER BY id_item DESC')->fetchAll();
        json_response(array_map('item_to_card', $items));
    }

    if ($method === 'GET') {
        $idItem = normalize_id($id);

        if (!$idItem) {
            json_response(['message' => 'id kartu tidak valid'], 400);
        }

        $item = run_query('SELECT * FROM item WHERE id_item = ?', [$idItem])->fetch();

        if (!$item) {
            json_response(['message' => 'Kartu tidak ditemukan'], 404);
        }

        json_response(item_to_card($item));
    }

    if ($method === 'POST') {
        $data = read_json_body();
        $name = trim((string) ($data['name'] ?? ''));
        $description = $data['description'] ?? null;

        if ($name === '') {
            json_response(['message' => 'name wajib diisi'], 400);
        }

        run_query('INSERT INTO item (name, description) VALUES (?, ?)', [$name, $description]);
        $newId = (int) database()->lastInsertId();
        $item = run_query('SELECT * FROM item WHERE id_item = ?', [$newId])->fetch();

        json_response(item_to_card($item), 201);
    }

    if ($method === 'PUT') {
        $idItem = normalize_id($id);
        $data = read_json_body();
        $name = trim((string) ($data['name'] ?? ''));
        $description = $data['description'] ?? null;

        if (!$idItem) {
            json_response(['message' => 'id kartu tidak valid'], 400);
        }

        if ($name === '') {
            json_response(['message' => 'name wajib diisi'], 400);
        }

        run_query('UPDATE item SET name = ?, description = ? WHERE id_item = ?', [$name, $description, $idItem]);
        $item = run_query('SELECT * FROM item WHERE id_item = ?', [$idItem])->fetch();

        if (!$item) {
            json_response(['message' => 'Kartu tidak ditemukan'], 404);
        }

        json_response(item_to_card($item));
    }

    if ($method === 'DELETE') {
        $idItem = normalize_id($id);

        if (!$idItem) {
            json_response(['message' => 'id kartu tidak valid'], 400);
        }

        run_query('DELETE FROM item WHERE id_item = ?', [$idItem]);
        json_response(['message' => 'Kartu berhasil dihapus']);
    }

    json_response(['message' => 'Method tidak didukung'], 405);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$segments = route_segments();
$resource = $segments[0] ?? 'health';
$id = $segments[1] ?? null;

try {
    switch ($resource) {
        case '':
        case 'health':
            json_response(['message' => 'API PHP card_manager berjalan']);
            break;
        case 'players':
            handle_players($method, $id);
            break;
        case 'items':
            handle_items($method);
            break;
        case 'quests':
            handle_quests($method);
            break;
        case 'cards':
            handle_cards($method, $id);
            break;
        default:
            json_response(['message' => 'Endpoint tidak ditemukan'], 404);
    }
} catch (PDOException $error) {
    handle_database_error($error);
}
