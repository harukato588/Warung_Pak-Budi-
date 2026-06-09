<div align="center">

<!-- Laravel Logo -->
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo"/>

<br/>
<br/>

<!-- Hacking-style title banner -->
```
╔══════════════════════════════════════════════════════════╗
║   ██╗  ██╗ █████╗  ██████╗██╗  ██╗██╗      █████╗██████╗  ║
║   ██║  ██║██╔══██╗██╔════╝██║ ██╔╝██║     ██╔══██╗██╔══██╗ ║
║   ███████║███████║██║     █████╔╝ ██║     ███████║██████╔╝ ║
║   ██╔══██║██╔══██║██║     ██╔═██╗ ██║     ██╔══██║██╔══██╗ ║
║   ██║  ██║██║  ██║╚██████╗██║  ██╗███████╗██║  ██║██████╔╝ ║
║   ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝╚═════╝  ║
║              L A B  —  E T H I C A L  H A C K I N G         ║
╚══════════════════════════════════════════════════════════╝
```

<br/>

<!-- Badges -->
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)
![Status](https://img.shields.io/badge/Status-Active-brightgreen?style=for-the-badge)
![Purpose](https://img.shields.io/badge/Purpose-Educational%20Only-orange?style=for-the-badge)

<br/>

> *"The quieter you become, the more you are able to hear."* — Kali Linux

</div>

---

## 🔐 Tentang Project Ini

**HackLab** adalah sebuah **web application lab** yang dibangun dengan framework **Laravel**, dirancang khusus sebagai lingkungan latihan yang aman untuk belajar **Ethical Hacking** dan **Penetration Testing**.

Lab ini menyediakan berbagai skenario kerentanan (*vulnerabilities*) yang sengaja dibuat untuk tujuan pembelajaran — membantu kamu memahami cara kerja serangan siber dari sudut pandang seorang attacker, sekaligus belajar cara mempertahankannya sebagai seorang defender.

> ⚠️ **DISCLAIMER:** Lab ini **hanya untuk tujuan edukasi**. Jangan gunakan teknik yang dipelajari di sini pada sistem yang bukan milikmu atau tanpa izin tertulis. Segala penyalahgunaan adalah tanggung jawab pengguna sepenuhnya.

---

## 🎯 Tujuan & Sasaran

```
[ TARGET ACQUIRED ]

  ✅  Memahami konsep dasar kerentanan web (OWASP Top 10)
  ✅  Latihan teknik penetration testing secara legal & aman
  ✅  Mengembangkan kemampuan sebagai ethical hacker
  ✅  Belajar dari perspektif attacker & defender
  ✅  Persiapan sertifikasi (CEH, OSCP, eJPT, dsb.)
```

---

## 🧰 Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| **Framework** | Laravel (PHP) |
| **Database** | MySQL / SQLite |
| **Frontend** | Blade + Tailwind CSS |
| **Auth** | Laravel Breeze |
| **Server** | Apache / Nginx |
| **Environment** | PHP 8.x |

---

## 🗂️ Struktur Lab & Modul

```
hacklab/
├── 🔴  SQLi Lab          — SQL Injection (Classic, Blind, Time-Based)
├── 🟠  XSS Lab           — Cross-Site Scripting (Reflected, Stored, DOM)
├── 🟡  IDOR Lab          — Insecure Direct Object Reference
├── 🟢  Auth Lab          — Broken Authentication & Session Management
├── 🔵  CSRF Lab          — Cross-Site Request Forgery
├── 🟣  File Upload Lab   — Unrestricted File Upload
├── ⚪  LFI/RFI Lab       — Local & Remote File Inclusion
└── 🔒  Secure Coding     — Contoh kode yang aman sebagai perbandingan
```

---

## ⚡ Instalasi & Setup

### Prasyarat

Pastikan sistem kamu sudah terinstall:
- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js & NPM

### Langkah Instalasi

```bash
# 1. Clone repository ini
git clone https://github.com/username/hacklab.git
cd hacklab

# 2. Install dependencies PHP
composer install

# 3. Install dependencies Node
npm install && npm run build

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Setup database di file .env, lalu jalankan migrasi
php artisan migrate --seed

# 7. Jalankan server lokal
php artisan serve
```

> 🌐 Akses lab di: `http://localhost:8000`

---

## 🛡️ Etika & Aturan Penggunaan

```
╔─────────────────────────────────────────────────────╗
│              ⚖️  KODE ETIK PENGGUNA                  │
├─────────────────────────────────────────────────────┤
│  [✓]  Gunakan HANYA di lingkungan lab ini            │
│  [✓]  Jangan deploy lab ini ke server publik         │
│  [✓]  Hormati privasi dan data orang lain            │
│  [✓]  Gunakan ilmu ini untuk kebaikan (blue team)    │
│  [✗]  JANGAN gunakan pada sistem tanpa izin          │
│  [✗]  JANGAN gunakan untuk tujuan ilegal             │
╚─────────────────────────────────────────────────────╝
```

---

## 📚 Referensi & Sumber Belajar

- 🌐 [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- 📖 [HackTricks](https://book.hacktricks.xyz/)
- 🖥️ [TryHackMe](https://tryhackme.com/)
- 💻 [HackTheBox](https://www.hackthebox.com/)
- 🎓 [PortSwigger Web Security Academy](https://portswigger.net/web-security)
- 🇮🇩 [Hacktrace](https://hacktrace.id/) — Platform CTF Lokal Indonesia

---

## 🤝 Kontribusi

Kontribusi sangat terbuka! Jika kamu ingin menambahkan modul baru, memperbaiki bug, atau meningkatkan dokumentasi:

1. Fork repository ini
2. Buat branch baru: `git checkout -b fitur/nama-modul`
3. Commit perubahan: `git commit -m 'feat: tambah modul XSS'`
4. Push ke branch: `git push origin fitur/nama-modul`
5. Buat Pull Request

---

## 📄 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE) — bebas digunakan untuk keperluan edukasi.

---

<div align="center">

**Dibuat dengan ❤️ untuk komunitas keamanan siber Indonesia**

```
[ STATUS: LEARNING IN PROGRESS... ]
[ ACCESS GRANTED — HAPPY HACKING! ]
```

</div>
