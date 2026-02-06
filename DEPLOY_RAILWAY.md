# 🚀 Deploy ke Railway - Quick Start

## ✅ Error "No version available for php 8.0" SUDAH DIPERBAIKI!

File `nixpacks.toml` sudah dibuat dengan konfigurasi PHP 8.2.

---

## 📋 Langkah Cepat

### 1️⃣ Commit & Push ke GitHub
```bash
git add .
git commit -m "Add Railway configuration"
git push origin main
```

### 2️⃣ Deploy di Railway
1. Buka https://railway.app
2. Login dengan GitHub
3. Klik **"New Project"** → **"Deploy from GitHub repo"**
4. Pilih repository: `blogapp-laravel-api`
5. Tunggu build selesai (2-5 menit)

### 3️⃣ Add MySQL Database
1. Klik **"New"** → **"Database"** → **"Add MySQL"**
2. Tunggu database ready

### 4️⃣ Setup Environment Variables
Klik service Laravel → tab **"Variables"** → **"RAW Editor"**, paste:

```env
APP_NAME=Blog API
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:yDO7W8M06QNxU3c1KF4RjCivmeMq54w3oYFJF0lz/Wg=
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
JWT_SECRET=Ue2rV2B20eRyKjyQ6xoSkXiq5Ok532JqRjX49xuVZE89ysYigh81L5BEsXi1WUQU
JWT_TTL=60
LOG_LEVEL=error
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

**PENTING**: Railway akan auto-replace `${{MySQL.MYSQL_HOST}}` dll dengan nilai actual dari MySQL service!

### 5️⃣ Run Migration
Install Railway CLI:
```bash
npm install -g @railway/cli
```

Login dan run migration:
```bash
railway login
railway link
railway run php artisan migrate --force
```

### 6️⃣ Generate Domain
1. Klik service Laravel → tab **"Settings"**
2. Scroll ke **"Domains"** → **"Generate Domain"**
3. Copy domain: `https://your-app.up.railway.app`

### 7️⃣ Update APP_URL
Di tab **"Variables"**, update:
```
APP_URL=https://your-app.up.railway.app
```

### 8️⃣ Test API
```bash
curl https://your-app.up.railway.app/api/posts
```

---

## 📖 Panduan Lengkap
Lihat: `.agent/workflows/deploy-railway.md`

---

## 🔧 Troubleshooting

**Build masih error?**
- Cek logs: Deployments → View Logs
- Pastikan `nixpacks.toml` sudah ter-commit

**Database error?**
- Pastikan MySQL service running (indikator hijau)
- Cek environment variables sudah benar

**500 Error?**
- Pastikan migration sudah dijalankan
- Cek APP_KEY dan JWT_SECRET sudah diset

---

## 💡 Tips

✅ Railway auto-deploy setiap push ke GitHub
✅ SSL/HTTPS gratis dan otomatis
✅ Free tier $5/bulan (cukup untuk testing)
✅ Logs real-time untuk debugging

**Good luck! 🚀**
