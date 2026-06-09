# ⚠️ VULNERABLE WEB APPLICATION - LAB LATIHAN

> **PERINGATAN**: Aplikasi ini **SENGAJA DIBUAT RENTAN** untuk tujuan edukasi bug hunting & ethical hacking.
> **JANGAN** deploy di server publik / production!

---

## 🎯 Daftar Kerentanan yang Ditanamkan

### 1. SQL Injection (SQLi)
- **Lokasi**: `GET /products?search=` — ProductController@index
- **Teknik**: Raw query tanpa parameterisasi
- **Contoh payload**: `' OR '1'='1` atau `' UNION SELECT 1,2,3,email,password,6 FROM users--`

### 2. Cross-Site Scripting / XSS (Reflected & Stored)
- **Reflected XSS**: `GET /products?search=<script>alert(1)</script>`
- **Stored XSS**: Nama produk & deskripsi ditampilkan tanpa escaping
- **Lokasi**: `products/index.blade.php`, `products/show.blade.php`

### 3. IDOR — Insecure Direct Object Reference
- **Lokasi**: `GET /order/{id}` — Tidak ada cek kepemilikan order
- **Contoh**: Login sebagai user A, lalu akses `/order/1` milik user B

### 4. Broken Access Control (Admin)
- **Lokasi**: `/admin/products/create` — Tidak ada pengecekan role admin
- **Contoh**: Semua user yang login bisa tambah/hapus produk

### 5. Mass Assignment
- **Lokasi**: Semua model (User, Product, Order, dll.)
- **Contoh**: POST dengan field `is_admin=1` atau `role=admin`

### 6. Insecure File Upload
- **Lokasi**: `POST /admin/products` — Upload gambar
- **Contoh**: Upload file .php sebagai shell, akses via `/storage/products/shell.php`

### 7. Price Manipulation / Business Logic Flaw
- **Lokasi**: `POST /checkout` — Harga diterima dari client
- **Contoh**: Kirim `total_price=1` untuk checkout seharga Rp 1

### 8. Negative Quantity / Inventory Manipulation
- **Lokasi**: `POST /cart/{product}` — Quantity bisa negatif
- **Contoh**: Kirim `quantity=-999` untuk memanipulasi stok

### 9. CSRF (Cross-Site Request Forgery)
- **Lokasi**: Beberapa route tanpa CSRF token
- **Contoh**: Buat halaman yang auto-submit form ke toko korban

### 10. Sensitive Data Exposure
- **Lokasi**: `GET /api/debug` — Menampilkan env, config, semua user & password hash

### 11. Broken Authentication — No Rate Limiting
- **Lokasi**: `POST /login` — Tidak ada limit percobaan login
- **Teknik**: Brute force bebas

### 12. Information Disclosure via Error
- **Lokasi**: APP_DEBUG=true, stack trace terbuka ke publik

---

## 🛡️ Cara Menggunakan Lab Ini

1. Jalankan: `php artisan serve`
2. Tools yang disarankan: Burp Suite, sqlmap, OWASP ZAP, Nikto
3. Temukan semua 12 kerentanan dan buat PoC
4. Gunakan hanya di environment lokal / lab terisolasi

---

*Dibuat murni untuk tujuan edukasi ethical hacking.*
