# ⚙️ Blog App — Laravel REST API

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![JWT](https://img.shields.io/badge/JWT-black?style=for-the-badge&logo=JSON%20web%20tokens)
![Railway](https://img.shields.io/badge/Railway-0B0D0E?style=for-the-badge&logo=railway&logoColor=white)

**Backend REST API untuk Blog App — mendukung autentikasi JWT, manajemen post, komentar, dan like.**  
*Dikonsumsi oleh [Blog App Android](https://github.com/kzherdinnn/blogapp-android-app) sebagai mobile client.*

🌐 **Live API:** `https://blogapp-laravel-api-production.up.railway.app`

</div>

---

## 🔗 Ekosistem Aplikasi

Proyek ini terdiri dari **dua repository** yang saling terhubung:

| Repository | Teknologi | Peran |
|---|---|---|
| **[blogapp-laravel-api](https://github.com/kzherdinnn/blogapp-laravel-api)** ← *Anda di sini* | Laravel 9, JWT Auth | REST API Backend |
| **[blogapp-android-app](https://github.com/kzherdinnn/blogapp-android-app)** | Java, Android SDK | Client Mobile |

> 💡 API ini menerima request dari Android app, memproses data, dan mengembalikan response dalam format **JSON**.  
> Semua endpoint (kecuali login & register) dilindungi dengan **JWT Authentication**.

---

## ✨ Fitur API

- 🔐 **JWT Authentication** — Login, Register, Logout dengan token aman
- 📝 **CRUD Posts** — Buat, baca, edit, dan hapus post dengan foto
- ❤️ **Like System** — Toggle like pada post
- 💬 **CRUD Comments** — Tambah, edit, hapus komentar
- 👤 **User Profile** — Update nama, lastname, dan foto profil
- 📂 **File Storage** — Upload & simpan gambar ke server
- 🔒 **Authorization** — Hanya pemilik yang bisa edit/hapus post/komentar
- 🌐 **CORS Support** — Mendukung akses dari berbagai origin

---

## 🛠️ Tech Stack

| Kategori | Teknologi | Versi |
|---|---|---|
| **Framework** | Laravel | 9.0 |
| **Language** | PHP | ^8.2 |
| **Authentication** | tymon/jwt-auth | 1.* |
| **Database** | MySQL | — |
| **CORS** | fruitcake/laravel-cors | ^3.0 |
| **Deployment** | Railway | — |

---

## 📡 API Endpoints

### 🔓 Public Routes (Tanpa Auth)

| Method | Endpoint | Deskripsi | Request Body |
|---|---|---|---|
| `POST` | `/api/login` | Login pengguna | `email`, `password` |
| `POST` | `/api/register` | Registrasi pengguna baru | `email`, `password` |

### 🔒 Protected Routes (Butuh JWT Token)

> Header yang diperlukan:  
> `Authorization: Bearer {token}`

#### 👤 User

| Method | Endpoint | Deskripsi | Request Body |
|---|---|---|---|
| `GET` | `/api/logout` | Logout & invalidate token | — |
| `POST` | `/api/save_user_info` | Update profil pengguna | `name`, `lastname`, `photo` (base64) |

#### 📝 Posts

| Method | Endpoint | Deskripsi | Request Body |
|---|---|---|---|
| `GET` | `/api/posts` | Ambil semua post | — |
| `GET` | `/api/posts/my_posts` | Ambil post milik saya | — |
| `POST` | `/api/posts/create` | Buat post baru | `desc`, `photo` (base64) |
| `POST` | `/api/posts/posts/{id}` | Edit post | `desc` |
| `DELETE` | `/api/posts/{id}` | Hapus post | — |
| `POST` | `/api/posts/like` | Toggle like post | `post_id` |
| `POST` | `/api/posts/comments` | Ambil komentar post | `post_id` |

#### 💬 Comments

| Method | Endpoint | Deskripsi | Request Body |
|---|---|---|---|
| `POST` | `/api/comments/create` | Tambah komentar | `post_id`, `comment` |
| `PUT` | `/api/comments/update` | Edit komentar | `comment_id`, `comment` |
| `DELETE` | `/api/comments/delete` | Hapus komentar | `comment_id` |

---

## 📤 Contoh Response

### Login Berhasil
```json
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "Herdin",
    "lastname": "Kz",
    "photo": "1234567890.jpg"
  }
}
```

### Semua Post
```json
{
  "success": true,
  "posts": [
    {
      "id": 1,
      "user_id": 1,
      "desc": "Hello World!",
      "photo": "1234567890.jpg",
      "created_at": "2024-02-18T10:00:00.000000Z",
      "user": { "id": 1, "name": "Herdin", "photo": "..." },
      "commentsCount": 3,
      "likesCount": 10,
      "selfLike": false
    }
  ]
}
```

---

## 🗂️ Struktur Project

```
blogapp-laravel-api/
│
├── 📁 app/
│   ├── 📁 Http/
│   │   ├── 📁 Controllers/Api/
│   │   │   ├── AuthController.php       # Login, Register, Logout, Update Profil
│   │   │   ├── PostsController.php      # CRUD Post
│   │   │   ├── CommentsController.php   # CRUD Komentar
│   │   │   └── LikesController.php      # Like/Unlike Post
│   │   └── 📁 Middleware/
│   │       └── JwtMiddleware.php        # Validasi JWT Token
│   ├── Post.php                         # Model Post
│   ├── User.php                         # Model User
│   ├── Comment.php                      # Model Comment
│   └── Like.php                         # Model Like
│
├── 📁 database/
│   └── 📁 migrations/                   # Skema database
│
├── 📁 routes/
│   └── api.php                          # Definisi semua API routes
│
├── 📁 storage/app/public/
│   ├── posts/                           # Foto post
│   └── profiles/                        # Foto profil
│
├── .env.example                         # Template konfigurasi environment
├── API_TESTING.md                       # Panduan testing API
├── DEPLOY_RAILWAY.md                    # Panduan deploy ke Railway
└── DEPLOY_INFINITYFREE.md              # Panduan deploy ke InfinityFree
```

---

## 🗄️ Skema Database

```
users
├── id           (PK)
├── email        (unique)
├── password     (hashed)
├── name
├── lastname
├── photo
└── timestamps

posts
├── id           (PK)
├── user_id      (FK → users)
├── desc
├── photo
└── timestamps

comments
├── id           (PK)
├── user_id      (FK → users)
├── post_id      (FK → posts)
├── comment
└── timestamps

likes
├── id           (PK)
├── user_id      (FK → users)
├── post_id      (FK → posts)
└── timestamps
```

---

## 🚀 Cara Menjalankan Lokal

### Persyaratan
- PHP ^8.2
- Composer
- MySQL / MariaDB
- XAMPP / Laragon / Herd

### 1. Clone Repository

```bash
git clone https://github.com/kzherdinnn/blogapp-laravel-api.git
cd blogapp-laravel-api
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Edit `.env`:
```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blogapp
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrate Database

```bash
php artisan migrate
```

### 5. Storage Link

```bash
php artisan storage:link
```

### 6. Jalankan Server

```bash
php artisan serve
```

API tersedia di: `http://127.0.0.1:8000/api`

---

## 📱 Menghubungkan ke Android App

Setelah server lokal berjalan, ubah BASE_URL di Android app:

```java
// blogapp-android-app → Constant.java

// Untuk emulator Android:
public static final String URL = "http://10.0.2.2:8000";

// Untuk device fisik (gunakan IP lokal PC):
public static final String URL = "http://192.168.x.x:8000";
```

> 📖 Lihat panduan lengkap setup Android: [blogapp-android-app](https://github.com/kzherdinnn/blogapp-android-app#-cara-menjalankan)

---

## ☁️ Deploy

### Railway (Recommended)

Lihat panduan lengkap: [DEPLOY_RAILWAY.md](./DEPLOY_RAILWAY.md)

### InfinityFree (Free Hosting)

Lihat panduan lengkap: [DEPLOY_INFINITYFREE.md](./DEPLOY_INFINITYFREE.md)

---

## 🧪 Testing API

Panduan lengkap testing endpoint ada di: [API_TESTING.md](./API_TESTING.md)

Atau jalankan script testing otomatis:

```powershell
# Windows PowerShell
.\test-api.ps1
```

---

## 🤝 Kontribusi

Pull request sangat disambut! Untuk perubahan besar, harap buka *issue* terlebih dahulu.

---

## 👨‍💻 Developer

**Herdin Kz**  
GitHub: [@kzherdinnn](https://github.com/kzherdinnn)

---

## 📄 Lisensi

MIT License — bebas digunakan untuk keperluan belajar dan pengembangan.
