## ⚠️ tolong jangan sebarkan tanpa seizin gueh, thanks

## 🚀 Installation Guide

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan project ini secara lokal:

---

### 1️⃣ Clone Repository

```bash
git clone https://github.com/fadhlihilmans/SistemSuratDisposisi.git
cd SistemSuratDisposisi
```

---

### 2️⃣ Install Dependencies

```bash
composer install
npm install
```

---

### 3️⃣ Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

Kemudian atur konfigurasi database dan setting lainnya sesuai kebutuhan di dalam file `.env` (seperti `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, dll).

---

### 4️⃣ Generate Application Key

```bash
php artisan key:generate
```

---

### 5️⃣ Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

### 6️⃣ LetsGoooo

```bash
php artisan serve
```


## 🛠️ Akses Admin

-   URL: `http://localhost:8000`
-   **Default Login Account**:
    -   username (pilih salah satu): `admin`, `kepala`, `sekretariat`, `pegawai_1`, `pegawai_2`
    -   Password: `12345678`

---

## ⚙️ Requirement

-   PHP 8.2
-   Laravel 12.x
-   Livewire
-   template admin :
```bash
https://github.com/stisla/stisla
```
