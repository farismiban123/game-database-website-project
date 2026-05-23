# Card Manager

Aplikasi manajemen kartu dengan frontend HTML/CSS/JavaScript dan backend PHP untuk MySQL.

## Struktur Project

```text
card-manager/
├── backend/
│   └── api/
│       ├── config.php
│       ├── index.php
│       └── router.php
├── database/
│   ├── schema.sql
│   └── seed.sql
└── frontend/
    ├── css/
    ├── js/
    └── pages/
```

## Cara Menjalankan Dengan XAMPP

1. Aktifkan Apache dan MySQL di XAMPP.
2. Import `database/schema.sql`, lalu `database/seed.sql` ke MySQL.
3. Pastikan database bernama `card_manager`.
4. Buka frontend lewat Apache/XAMPP, bukan Live Server.

Contoh URL jika project ada di `htdocs`:

```text
http://localhost/game-database-website-project/card-manager/frontend/js/components/player_card.html
```

API PHP tersedia di:

```text
http://localhost/game-database-website-project/card-manager/backend/api/index.php/api/health
```

## Kalau Tetap Pakai Live Server

Live Server tidak bisa menjalankan PHP. Jalankan API PHP terpisah:

```bash
cd card-manager
npm run dev:backend
```

Lalu buka frontend seperti biasa lewat Live Server.

Tanpa npm, bisa juga jalankan langsung:

```powershell
cd card-manager
C:\xampp\php\php.exe -S 127.0.0.1:5000 -t backend/api backend/api/router.php
```

## Konfigurasi Database

Default koneksi ada di `backend/api/config.php`:

```php
host: 127.0.0.1
database: card_manager
username: root
password: kosong
port: 3306
```
