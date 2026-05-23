<?php

declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = require dirname(__DIR__, 2) . '/backend/api/config.php';
    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
        $config['host'],
        $config['port'],
        $config['database']
    );

    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function fetch_all(string $sql, array $params = []): array
{
    $statement = db()->prepare($sql);
    $statement->execute($params);
    return $statement->fetchAll();
}

function fetch_one(string $sql, array $params = []): ?array
{
    $statement = db()->prepare($sql);
    $statement->execute($params);
    $row = $statement->fetch();

    return $row === false ? null : $row;
}

function execute_query(string $sql, array $params = []): void
{
    $statement = db()->prepare($sql);
    $statement->execute($params);
}

function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function id_from_get(string $key): ?int
{
    $id = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
    return $id === false ? null : $id;
}

function format_date_id(?string $date): string
{
    if (!$date) {
        return '-';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return $date;
    }

    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    return date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp)] . ' ' . date('Y', $timestamp);
}

function page_start(string $title, string $bodyClass, array $styles = []): void
{
    $styles[] = 'css/app.css';
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= e($title) ?></title>
<?php foreach ($styles as $style): ?>
  <link rel="stylesheet" href="<?= e($style) ?>" />
<?php endforeach; ?>
</head>
<body class="<?= e($bodyClass) ?>">
<?php
}

function page_end(): void
{
    ?>
</body>
</html>
<?php
}

function nav_link(string $href, string $label): string
{
    $current = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $class = $current === $href ? ' app-nav__link--active' : '';

    return '<a class="app-nav__link' . $class . '" href="' . e($href) . '">' . e($label) . '</a>';
}

function app_header(): void
{
    ?>
  <header class="app-header">
    <a class="app-brand" href="index.php">Card Manager</a>
    <nav class="app-nav" aria-label="Navigasi utama">
      <?= nav_link('players.php', 'Players') ?>
      <?= nav_link('items.php', 'Items') ?>
      <?= nav_link('quests.php', 'Quests') ?>
      <?= nav_link('add.php', 'Add Data') ?>
    </nav>
  </header>
<?php
}

function render_error_page(Throwable $error): void
{
    page_start('Database Error', 'app-page');
    app_header();
    ?>
  <main class="app-shell">
    <section class="app-message app-message--error">
      <h1>Database belum bisa diakses</h1>
      <p>Pastikan Apache dan MySQL XAMPP aktif, lalu import <code>schema.sql</code> dan <code>seed.sql</code>.</p>
      <p class="app-message__detail"><?= e($error->getMessage()) ?></p>
    </section>
  </main>
<?php
    page_end();
    exit;
}
