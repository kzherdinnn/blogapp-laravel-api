---
description: Panduan lengkap deploy Laravel API ke Railway
---

# 🚀 Deploy Laravel API ke Railway

Railway adalah platform cloud modern yang sangat cocok untuk Laravel. Gratis untuk memulai dan sangat mudah digunakan!

---

## ✅ Keuntungan Railway

- ✅ **Auto-deploy dari GitHub** - Push code langsung deploy
- ✅ **Database MySQL gratis** - Sudah include database
- ✅ **SSL/HTTPS otomatis** - Gratis dan auto-renew
- ✅ **Environment variables** - Mudah manage config
- ✅ **Logs real-time** - Debug mudah
- ✅ **Free tier $5/bulan** - Cukup untuk testing

---

## 📋 Langkah 1: Persiapan Project

### 1.1 Pastikan File Konfigurasi Ada

File-file berikut sudah dibuat:
- ✅ `nixpacks.toml` - Konfigurasi Railway (PHP 8.2)
- ✅ `Procfile` - Start command
- ✅ `.env.example` - Template environment variables

### 1.2 Commit dan Push ke GitHub

```bash
# Add semua file
git add .

# Commit
git commit -m "Add Railway configuration"

# Push ke GitHub
git push origin main
```

**PENTING**: Pastikan repository sudah ada di GitHub!

Jika belum punya repository:
```bash
# Initialize git (jika belum)
git init

# Add remote
git remote add origin https://github.com/username/blogapp-laravel-api.git

# Push
git branch -M main
git push -u origin main
```

---

## 📋 Langkah 2: Setup Railway Account

### 2.1 Daftar Railway
1. Kunjungi: https://railway.app
2. Klik **"Start a New Project"** atau **"Login"**
3. Login dengan **GitHub** (recommended)
4. Authorize Railway untuk akses GitHub

### 2.2 Verifikasi Email
Railway akan kirim email verifikasi. Klik link di email.

---

## 📋 Langkah 3: Deploy dari GitHub

### 3.1 Create New Project
1. Di Railway dashboard, klik **"New Project"**
2. Pilih **"Deploy from GitHub repo"**
3. Pilih repository: `blogapp-laravel-api`
4. Railway akan otomatis detect Laravel dan mulai deploy

### 3.2 Tunggu Build Selesai
Railway akan:
- ✅ Detect PHP dan Laravel
- ✅ Install dependencies via Composer
- ✅ Build aplikasi
- ✅ Deploy ke server

Proses ini memakan waktu **2-5 menit**.

---

## 📋 Langkah 4: Setup Database MySQL

### 4.1 Add MySQL Database
1. Di project Railway, klik **"New"** → **"Database"** → **"Add MySQL"**
2. Railway akan provision database baru
3. Tunggu sampai database ready (indikator hijau)

### 4.2 Catat Kredensial Database
1. Klik service **MySQL**
2. Klik tab **"Variables"**
3. Catat nilai berikut:
   - `MYSQL_HOST`
   - `MYSQL_PORT`
   - `MYSQL_DATABASE`
   - `MYSQL_USER`
   - `MYSQL_PASSWORD`
   - `MYSQL_URL` (connection string lengkap)

---

## 📋 Langkah 5: Setup Environment Variables

### 5.1 Buka Service Laravel
1. Klik service Laravel Anda (biasanya nama repo)
2. Klik tab **"Variables"**

### 5.2 Tambahkan Environment Variables

Klik **"New Variable"** dan tambahkan satu per satu:

#### **Application Settings**
```
APP_NAME=Blog API
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:yDO7W8M06QNxU3c1KF4RjCivmeMq54w3oYFJF0lz/Wg=
```

**PENTING**: Ganti `APP_KEY` dengan nilai dari `.env` lokal Anda!

#### **Database Settings**
```
DB_CONNECTION=mysql
DB_HOST=<MYSQL_HOST dari Railway>
DB_PORT=<MYSQL_PORT dari Railway>
DB_DATABASE=<MYSQL_DATABASE dari Railway>
DB_USERNAME=<MYSQL_USER dari Railway>
DB_PASSWORD=<MYSQL_PASSWORD dari Railway>
```

**ATAU** gunakan connection string:
```
DATABASE_URL=<MYSQL_URL dari Railway>
```

#### **JWT Settings**
```
JWT_SECRET=Ue2rV2B20eRyKjyQ6xoSkXiq5Ok532JqRjX49xuVZE89ysYigh81L5BEsXi1WUQU
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

**PENTING**: Ganti `JWT_SECRET` dengan nilai dari `.env` lokal Anda!

#### **Other Settings**
```
LOG_CHANNEL=stack
LOG_LEVEL=error
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 5.3 Cara Cepat - Bulk Add Variables

Railway juga support **Raw Editor**:
1. Klik **"RAW Editor"** di tab Variables
2. Paste semua variables sekaligus:

```env
APP_NAME=Blog API
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:yDO7W8M06QNxU3c1KF4RjCivmeMq54w3oYFJF0lz/Wg=
DB_CONNECTION=mysql
DB_HOST=containers-us-west-123.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=your-password-here
JWT_SECRET=Ue2rV2B20eRyKjyQ6xoSkXiq5Ok532JqRjX49xuVZE89ysYigh81L5BEsXi1WUQU
JWT_TTL=60
JWT_REFRESH_TTL=20160
LOG_CHANNEL=stack
LOG_LEVEL=error
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

3. Klik **"Update Variables"**

**Railway akan otomatis redeploy** setelah update variables!

---

## 📋 Langkah 6: Run Database Migration

### 6.1 Akses Railway CLI (Opsi 1 - Recommended)

**Install Railway CLI:**
```bash
# Windows (via npm)
npm install -g @railway/cli

# Atau via PowerShell
iwr https://railway.app/install.ps1 | iex
```

**Login dan Run Migration:**
```bash
# Login
railway login

# Link ke project
railway link

# Run migration
railway run php artisan migrate --force

# Seed database (optional)
railway run php artisan db:seed --force
```

### 6.2 Via Railway Dashboard (Opsi 2)

1. Klik service Laravel
2. Klik tab **"Deployments"**
3. Klik deployment yang aktif (yang hijau)
4. Klik **"View Logs"**
5. Scroll ke bawah, cari tombol **"Shell"** atau **"Terminal"**
6. Jalankan command:
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 6.3 Import Database dari Lokal (Opsi 3)

Jika sudah punya data di lokal:

**Export dari lokal:**
```bash
mysqldump -u root blogapp_laravel > database_export.sql
```

**Import ke Railway:**
1. Install MySQL client di lokal
2. Connect ke Railway MySQL:
```bash
mysql -h <MYSQL_HOST> -P <MYSQL_PORT> -u <MYSQL_USER> -p<MYSQL_PASSWORD> <MYSQL_DATABASE> < database_export.sql
```

---

## 📋 Langkah 7: Setup Domain & SSL

### 7.1 Generate Public Domain
1. Klik service Laravel
2. Klik tab **"Settings"**
3. Scroll ke **"Domains"**
4. Klik **"Generate Domain"**
5. Railway akan generate domain: `your-app.up.railway.app`

**SSL/HTTPS otomatis aktif!** ✅

### 7.2 Update APP_URL
1. Kembali ke tab **"Variables"**
2. Update `APP_URL`:
```
APP_URL=https://your-app.up.railway.app
```

Railway akan auto-redeploy.

### 7.3 Custom Domain (Optional)
Jika punya domain sendiri:
1. Di tab Settings → Domains
2. Klik **"Custom Domain"**
3. Masukkan domain Anda (contoh: `api.yourdomain.com`)
4. Update DNS di domain registrar:
   - Type: `CNAME`
   - Name: `api` (atau subdomain lain)
   - Value: `your-app.up.railway.app`
5. Tunggu DNS propagation (5-30 menit)

---

## 📋 Langkah 8: Testing

### 8.1 Test API Endpoints

**Base URL:** `https://your-app.up.railway.app`

**Test endpoints:**
```bash
# Get all posts
curl https://your-app.up.railway.app/api/posts

# Register user
curl -X POST https://your-app.up.railway.app/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST https://your-app.up.railway.app/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### 8.2 Check Logs
Jika ada error:
1. Klik service Laravel
2. Klik tab **"Deployments"**
3. Klik deployment aktif
4. Klik **"View Logs"**
5. Cari error messages

---

## 📋 Langkah 9: Auto-Deploy Setup

### 9.1 Enable Auto-Deploy (Sudah Aktif Default)
Railway otomatis deploy setiap kali Anda push ke GitHub!

**Workflow:**
```bash
# Edit code
# ...

# Commit
git add .
git commit -m "Update feature"

# Push
git push origin main

# Railway otomatis detect dan deploy! 🚀
```

### 9.2 Deploy Specific Branch
Default Railway deploy dari branch `main`. Untuk ganti:
1. Klik service Laravel
2. Klik tab **"Settings"**
3. Scroll ke **"Source"**
4. Ganti **"Branch"** ke branch lain (contoh: `production`)

---

## 🔧 Troubleshooting

### Error: "No version available for php 8.0"
✅ **SOLVED!** File `nixpacks.toml` sudah dibuat dengan PHP 8.2.

### Error: "500 Internal Server Error"
**Penyebab:**
- Environment variables belum diset
- Database belum di-migrate
- APP_KEY belum diset

**Solusi:**
1. Cek tab **"Variables"** - pastikan semua env vars ada
2. Run migration: `railway run php artisan migrate --force`
3. Cek logs untuk error detail

### Error: "Database connection failed"
**Solusi:**
1. Pastikan MySQL service sudah running (indikator hijau)
2. Cek kredensial database di Variables
3. Pastikan `DB_HOST`, `DB_PORT`, dll sudah benar

### Error: "JWT secret not set"
**Solusi:**
1. Tambahkan `JWT_SECRET` di Variables
2. Atau run: `railway run php artisan jwt:secret --force`

### Build Failed
**Solusi:**
1. Cek logs untuk error detail
2. Pastikan `composer.json` valid
3. Pastikan semua dependencies compatible dengan PHP 8.2

### Storage Permission Error
**Solusi:**
Railway otomatis set permission. Jika masih error:
```bash
railway run chmod -R 775 storage bootstrap/cache
```

---

## 📊 Monitoring & Maintenance

### View Logs
```bash
# Via CLI
railway logs

# Atau via dashboard: Deployments → View Logs
```

### Restart Service
```bash
# Via CLI
railway restart

# Atau via dashboard: Settings → Restart
```

### Run Artisan Commands
```bash
# Via CLI
railway run php artisan <command>

# Contoh:
railway run php artisan cache:clear
railway run php artisan config:cache
railway run php artisan route:cache
```

---

## 💰 Pricing & Limits

### Free Tier
- **$5 credit/bulan** (gratis)
- Cukup untuk:
  - 1 web service (Laravel)
  - 1 MySQL database
  - Traffic moderate
  - ~500 hours runtime

### Jika Melebihi Free Tier
- Tambahkan payment method
- Pay-as-you-go: ~$0.000231/GB-hour
- Atau upgrade ke **Hobby Plan** ($5/bulan untuk $5 credit + extra features)

### Tips Hemat
- Set **"Sleep on Idle"** jika tidak butuh 24/7 uptime
- Optimize database queries
- Enable caching

---

## 🎯 Checklist Deploy

- [ ] File `nixpacks.toml`, `Procfile`, `.env.example` sudah ada
- [ ] Code sudah di-push ke GitHub
- [ ] Railway account sudah dibuat
- [ ] Project Railway sudah dibuat dari GitHub repo
- [ ] MySQL database sudah ditambahkan
- [ ] Environment variables sudah diset (APP_KEY, DB_*, JWT_SECRET)
- [ ] Database migration sudah dijalankan
- [ ] Public domain sudah di-generate
- [ ] APP_URL sudah di-update dengan domain Railway
- [ ] API endpoints sudah di-test dan berfungsi
- [ ] Auto-deploy sudah aktif

---

## 🆘 Bantuan Lebih Lanjut

- **Railway Docs**: https://docs.railway.app
- **Railway Discord**: https://discord.gg/railway
- **Laravel Docs**: https://laravel.com/docs

---

## 🚀 Next Steps

Setelah deploy berhasil:
1. **Setup CORS** jika ada frontend
2. **Enable caching** untuk performance
3. **Setup monitoring** (Railway punya built-in metrics)
4. **Backup database** secara berkala
5. **Setup CI/CD** untuk testing otomatis

**Selamat! API Anda sudah live di Railway! 🎉**
