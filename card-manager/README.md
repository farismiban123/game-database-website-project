# Card Manager

Aplikasi manajemen kartu — Monorepo dengan Frontend (HTML/CSS/JS) dan Backend (Express).

## Struktur Project

```
card-manager/
├── frontend/
│   ├── index.html           ← Halaman utama
│   ├── pages/
│   │   ├── collection.html  ← Halaman koleksi
│   │   └── detail.html      ← Halaman detail kartu
│   ├── css/
│   │   ├── style.css        ← Stylesheet utama
│   │   └── variables.css    ← CSS variables (warna, dsb)
│   └── js/
│       ├── app.js           ← Logic utama
│       ├── components/
│       │   ├── card.js      ← Render kartu
│       │   └── modal.js     ← Buka/tutup modal
│       └── utils/
│           ├── api.js       ← Fetch ke backend
│           └── helpers.js   ← Format tanggal, filter, dll
│
└── backend/
    ├── src/
    │   ├── server.js
    │   ├── routes/
    │   ├── controllers/
    │   ├── models/
    │   ├── middleware/
    │   └── config/
    └── package.json
```

## Cara Menjalankan

### Install semua dependency
```bash
npm run install:all
```

### Jalankan keduanya sekaligus
```bash
npm install
npm run dev
```

### Atau satu per satu
```bash
npm run dev:frontend   # http://localhost:5173
npm run dev:backend    # http://localhost:5000
```

> Frontend bisa juga dibuka langsung dengan double-click `frontend/index.html`
> (tanpa backend, data dummy akan otomatis dipakai)
