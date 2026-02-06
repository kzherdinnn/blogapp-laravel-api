# 🚀 Panduan Cepat Deploy ke InfinityFree

## ⚠️ PERINGATAN
InfinityFree **TIDAK DIREKOMENDASIKAN** untuk Laravel karena:
- ❌ Tidak support Composer
- ❌ Tidak ada SSH access
- ❌ Keterbatasan PHP extensions
- ❌ Performance terbatas

**Alternatif yang lebih baik:**
- ✅ **Railway.app** (Free tier, auto-deploy dari GitHub)
- ✅ **Render.com** (Free tier, auto-deploy dari GitHub)
- ✅ **Hostinger** (~Rp 20k/bulan, support Laravel penuh)
- ✅ **Niagahoster** (~Rp 30k/bulan, support Laravel penuh)

---

## 📋 Checklist Deploy (Jika tetap mau pakai InfinityFree)

### 1️⃣ Persiapan Lokal
- [ ] Export database: Jalankan `export_database.bat` atau via phpMyAdmin
- [ ] Edit `config.php`: Isi kredensial database InfinityFree
- [ ] Test lokal masih jalan

### 2️⃣ Setup InfinityFree
- [ ] Daftar di https://infinityfree.net
- [ ] Buat hosting account baru
- [ ] Catat kredensial database (MySQL hostname, database name, username, password)
- [ ] Catat kredensial FTP

### 3️⃣ Upload Files
- [ ] Download FileZilla: https://filezilla-project.org/
- [ ] Connect ke FTP InfinityFree
- [ ] Hapus semua file di folder `htdocs`
- [ ] Upload **SEMUA** file Laravel ke `htdocs`
- [ ] Set permission folder `storage` → 755 atau 777
- [ ] Set permission folder `bootstrap/cache` → 755 atau 777

### 4️⃣ Setup Database
- [ ] Buka phpMyAdmin di InfinityFree
- [ ] Import file SQL yang sudah di-export
- [ ] Verifikasi semua tabel ter-import

### 5️⃣ Update Config
- [ ] Edit `config.php` di server (via File Manager atau FTP)
- [ ] Ganti `app_url` dengan domain InfinityFree Anda
- [ ] Ganti `db_host`, `db_database`, `db_username`, `db_password`
- [ ] Save file

### 6️⃣ Testing
- [ ] Akses domain: `http://your-domain.infinityfreeapp.com`
- [ ] Test API: `http://your-domain.infinityfreeapp.com/api/posts`
- [ ] Test register: `POST /api/register`
- [ ] Test login: `POST /api/login`

### 7️⃣ Troubleshooting
Jika error 500:
- [ ] Cek permission folder `storage` dan `bootstrap/cache`
- [ ] Cek error log di InfinityFree Control Panel
- [ ] Pastikan `config.php` sudah benar

Jika database error:
- [ ] Cek kredensial database di `config.php`
- [ ] Pastikan database sudah di-import
- [ ] Test koneksi database via phpMyAdmin

---

## 📖 Dokumentasi Lengkap
Lihat file: `.agent/workflows/hosting-infinityfree.md`

---

## 🆘 Butuh Bantuan?
1. Cek error logs di InfinityFree Control Panel → Error Logs
2. Baca dokumentasi: https://forum.infinityfree.net
3. Atau gunakan hosting alternatif yang lebih support Laravel

---

## 📁 File Penting
- `config.php` - Konfigurasi untuk InfinityFree (ganti .env)
- `.htaccess` - Redirect ke folder public
- `export_database.bat` - Script export database
- `.agent/workflows/hosting-infinityfree.md` - Panduan lengkap

**Good luck! 🚀**
