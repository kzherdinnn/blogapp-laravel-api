---
description: Langkah-langkah hosting Laravel API di InfinityFree
---

# 🚀 Panduan Hosting Laravel API di InfinityFree

## ⚠️ PERINGATAN PENTING
InfinityFree memiliki keterbatasan untuk aplikasi Laravel:
- Tidak mendukung Composer secara langsung
- Tidak ada akses SSH/command line
- Keterbatasan PHP extensions
- Tidak mendukung .env file dengan baik
- **REKOMENDASI**: Gunakan hosting berbayar seperti Hostinger, Niagahoster, atau platform cloud seperti Railway, Render, atau Heroku untuk aplikasi Laravel.

Namun, jika tetap ingin mencoba InfinityFree, ikuti langkah-langkah berikut:

---

## 📋 Langkah 1: Persiapan Lokal

### 1.1 Install Dependencies Production
```bash
composer install --optimize-autoloader --no-dev
```

### 1.2 Generate Autoload Optimized
```bash
composer dump-autoload --optimize
```

### 1.3 Clear dan Cache Configuration
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 1.4 Optimize untuk Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📋 Langkah 2: Modifikasi File untuk InfinityFree

### 2.1 Buat file `.htaccess` di root project
Buat file `.htaccess` di root folder (bukan di `public/`) dengan isi:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 2.2 Update file `public/.htaccess`
Pastikan file `public/.htaccess` sudah ada dan berisi:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 2.3 Buat file `config.php` untuk mengganti `.env`
Buat file `config.php` di root project:

```php
<?php
return [
    'app_name' => 'Blog API',
    'app_env' => 'production',
    'app_debug' => false,
    'app_url' => 'http://your-domain.infinityfreeapp.com',
    'app_key' => 'base64:YOUR_APP_KEY_HERE',
    
    'db_connection' => 'mysql',
    'db_host' => 'sql123.infinityfree.com', // Ganti dengan host database Anda
    'db_port' => '3306',
    'db_database' => 'your_database_name',
    'db_username' => 'your_database_username',
    'db_password' => 'your_database_password',
    
    'jwt_secret' => 'YOUR_JWT_SECRET_HERE',
    'jwt_ttl' => 60,
];
```

### 2.4 Update `bootstrap/app.php` atau buat custom config loader
Tambahkan di awal file `bootstrap/app.php` (sebelum `$app = new Illuminate\Foundation\Application`):

```php
// Load custom config for InfinityFree
if (file_exists(__DIR__.'/../config.php')) {
    $customConfig = require __DIR__.'/../config.php';
    foreach ($customConfig as $key => $value) {
        $_ENV[strtoupper($key)] = $value;
        putenv(strtoupper($key).'='.$value);
    }
}
```

---

## 📋 Langkah 3: Daftar dan Setup InfinityFree

### 3.1 Daftar Akun InfinityFree
1. Kunjungi https://infinityfree.net
2. Klik "Sign Up" atau "Create Account"
3. Isi form pendaftaran dengan email Anda
4. Verifikasi email Anda

### 3.2 Buat Hosting Account
1. Login ke InfinityFree
2. Klik "Create Account" di dashboard
3. Pilih subdomain gratis atau gunakan domain sendiri
4. Tunggu proses pembuatan account (biasanya beberapa menit)

### 3.3 Catat Informasi Database
Setelah account dibuat, catat informasi berikut:
- **MySQL Hostname**: (contoh: sql123.infinityfree.com)
- **MySQL Database Name**: (contoh: if0_12345678_blogapi)
- **MySQL Username**: (contoh: if0_12345678)
- **MySQL Password**: (password yang Anda buat)

---

## 📋 Langkah 4: Upload File ke InfinityFree

### 4.1 Akses File Manager atau FTP
**Opsi A: Menggunakan File Manager**
1. Login ke InfinityFree Control Panel
2. Klik "File Manager"
3. Masuk ke folder `htdocs`

**Opsi B: Menggunakan FTP (Lebih Cepat)**
1. Download FileZilla: https://filezilla-project.org/
2. Buka FileZilla
3. Masukkan kredensial FTP dari InfinityFree:
   - Host: `ftpupload.net` atau sesuai yang diberikan
   - Username: (dari InfinityFree)
   - Password: (dari InfinityFree)
   - Port: 21

### 4.2 Upload Semua File Laravel
1. **HAPUS semua file di folder `htdocs`** (termasuk default.php, dll)
2. Upload **SEMUA file dan folder** dari project Laravel Anda ke folder `htdocs`
3. Pastikan struktur folder seperti ini:
   ```
   htdocs/
   ├── app/
   ├── bootstrap/
   ├── config/
   ├── database/
   ├── public/
   ├── resources/
   ├── routes/
   ├── storage/
   ├── vendor/
   ├── .htaccess
   ├── artisan
   ├── composer.json
   ├── config.php
   └── ...
   ```

### 4.3 Set Permission untuk Folder Storage
1. Klik kanan folder `storage` → Properties/Permissions
2. Set permission ke **755** atau **777**
3. Lakukan hal yang sama untuk folder `bootstrap/cache`

---

## 📋 Langkah 5: Setup Database

### 5.1 Buat Database di InfinityFree
1. Login ke InfinityFree Control Panel
2. Klik "MySQL Databases"
3. Buat database baru (jika belum ada)
4. Catat nama database, username, dan password

### 5.2 Import Database
1. Klik "phpMyAdmin" di InfinityFree Control Panel
2. Login dengan kredensial database Anda
3. Pilih database yang sudah dibuat
4. Klik tab "Import"
5. Upload file SQL dump dari database lokal Anda

**Cara export database lokal:**
```bash
# Di lokal, jalankan di terminal:
# Opsi 1: Via phpMyAdmin (lebih mudah)
# - Buka http://localhost/phpmyadmin
# - Pilih database
# - Klik Export
# - Pilih "Quick" atau "Custom"
# - Klik "Go"

# Opsi 2: Via command line
mysqldump -u root -p blogapi > blogapi_backup.sql
```

### 5.3 Update file `config.php`
Update file `config.php` dengan informasi database InfinityFree:

```php
'db_host' => 'sql123.infinityfree.com', // Ganti dengan host Anda
'db_database' => 'if0_12345678_blogapi', // Ganti dengan nama database Anda
'db_username' => 'if0_12345678', // Ganti dengan username Anda
'db_password' => 'your_password_here', // Ganti dengan password Anda
```

Upload ulang file `config.php` ke server.

---

## 📋 Langkah 6: Testing dan Troubleshooting

### 6.1 Test Website
1. Buka browser
2. Akses domain Anda: `http://your-domain.infinityfreeapp.com`
3. Test endpoint API: `http://your-domain.infinityfreeapp.com/api/posts`

### 6.2 Troubleshooting Common Issues

**Error 500 Internal Server Error:**
- Cek permission folder `storage` dan `bootstrap/cache` (set ke 755 atau 777)
- Pastikan file `.htaccess` ada di root dan di folder `public`
- Cek error log di InfinityFree Control Panel → Error Logs

**Database Connection Error:**
- Pastikan kredensial database di `config.php` benar
- Pastikan database sudah di-import
- Cek apakah IP server diizinkan (InfinityFree biasanya auto-allow)

**404 Not Found untuk semua route:**
- Pastikan mod_rewrite aktif (biasanya sudah aktif di InfinityFree)
- Cek file `.htaccess` di root dan `public/`
- Pastikan semua file sudah terupload dengan benar

**JWT Token Error:**
- Pastikan `jwt_secret` di `config.php` sama dengan yang di lokal
- Generate ulang JWT secret jika perlu: `php artisan jwt:secret`

**CORS Error:**
- Update file `config/cors.php` untuk allow origin dari frontend Anda
- Atau update di `app/Http/Kernel.php` untuk middleware CORS

---

## 📋 Langkah 7: Optimasi dan Keamanan

### 7.1 Disable Debug Mode
Pastikan di `config.php`:
```php
'app_debug' => false,
```

### 7.2 Protect Sensitive Files
Tambahkan di `.htaccess` root:
```apache
# Protect sensitive files
<FilesMatch "^(config\.php|\.env|composer\.json|composer\.lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### 7.3 Enable HTTPS (Jika Tersedia)
InfinityFree menyediakan SSL gratis:
1. Login ke Control Panel
2. Klik "SSL Certificates"
3. Install SSL certificate
4. Update `app_url` di `config.php` menjadi `https://`

---

## 🎯 Alternatif Hosting yang Lebih Baik

Jika mengalami masalah dengan InfinityFree, pertimbangkan hosting berikut:

### Hosting Berbayar (Murah):
1. **Hostinger** (~Rp 20.000/bulan)
   - Support SSH, Composer, Laravel
   - https://hostinger.co.id

2. **Niagahoster** (~Rp 30.000/bulan)
   - Support Laravel
   - https://niagahoster.co.id

3. **DomaiNesia** (~Rp 25.000/bulan)
   - Support Laravel
   - https://domainesia.com

### Platform Cloud (Gratis/Freemium):
1. **Railway** (Free tier tersedia)
   - Auto-deploy dari GitHub
   - https://railway.app

2. **Render** (Free tier tersedia)
   - Auto-deploy dari GitHub
   - https://render.com

3. **Fly.io** (Free tier tersedia)
   - Support Docker
   - https://fly.io

4. **Vercel** (Gratis, tapi perlu setup khusus untuk Laravel)
   - https://vercel.com

---

## 📝 Checklist Deploy

- [ ] Install dependencies production (`composer install --optimize-autoloader --no-dev`)
- [ ] Buat file `.htaccess` di root
- [ ] Buat file `config.php` dengan kredensial database
- [ ] Daftar akun InfinityFree
- [ ] Buat hosting account
- [ ] Catat informasi database
- [ ] Upload semua file Laravel via FTP/File Manager
- [ ] Set permission folder `storage` dan `bootstrap/cache` (755/777)
- [ ] Buat dan import database
- [ ] Update `config.php` dengan kredensial database InfinityFree
- [ ] Test website dan API endpoints
- [ ] Enable SSL (jika tersedia)
- [ ] Disable debug mode
- [ ] Protect sensitive files

---

## 🆘 Bantuan Lebih Lanjut

Jika mengalami kesulitan, silakan:
1. Cek error logs di InfinityFree Control Panel
2. Cek dokumentasi InfinityFree: https://forum.infinityfree.net
3. Atau pertimbangkan untuk menggunakan hosting alternatif yang lebih mendukung Laravel

**Good luck! 🚀**
