---
description: Panduan Lengkap Deploy ke Railway
---

# 🚀 Panduan Deploy Laravel ke Railway

## 1️⃣ Persiapan Code (Sudah Dilakukan)
Saya telah melakukan update otomatis pada:
1. `nixpacks.toml`: Menambahkan script `php artisan storage:link` agar folder storage terhubung dengan publik.
2. `config/filesystems.php`: Memastikan konfigurasi disk 'public' sudah benar.
3. `PostsController` & `AuthController`: Mengupdate logika penyimpanan gambar agar lebih rapi menggunakan `Storage::disk('public')`.

## 2️⃣ Upload ke GitHub
Pastikan semua perubahan sudah di-push ke GitHub:
```bash
git add .
git commit -m "Fix storage link and config for Railway"
git push
```

## 3️⃣ Setting Environment Variables di Railway
Buka dashboard Railway, masuk ke project Anda, lalu ke tab **Variables**. Tambahkan/Update variabel berikut:

| Variable | Value (Contoh) | Deskripsi |
|----------|---------------|-----------|
| `APP_URL` | `https://web-production-xxxx.up.railway.app` | **SANGAT PENTING!** URL domain dari Railway Anda (tanpa slash di akhir) |
| `FILESYSTEM_DRIVER` | `public` | Agar default penyimpanan ke public disk |
| `APP_DEBUG` | `true` | (Opsional) Ubah ke false jika sudah production |

**⚠️ Note tentang APP_URL:**
Jika `APP_URL` tidak diisi dengan benar, gambar akan menggunakan `http://localhost`, sehingga tidak muncul di browser.

## 4️⃣ Persistence (Agar Gambar Tidak Hilang saat Redeploy)
Secara default di Railway, file yang diupload akan **HILANG** setiap kali Anda melakukan redeploy (karena server di-reset).

Untuk menyimpan gambar secara permanen, Anda harus menambahkan **Volume**:
1. Di Dashboard Railway, klik kanan pada kanvas kosong -> **New** -> **Volume**.
2. Klik services Laravel Anda -> **Settings** -> **Service Domains / Networking** (bukan, cari bagian **Mounts** atau **Storage** di settings service).
3. Mount Volume tersebut ke path: `/app/storage/app/public`

*Hanya lakukan step 4 ini jika Anda ingin gambar bertahan selamanya. Untuk testing awal, step 1-3 sudah cukup untuk memunculkan gambar.*
