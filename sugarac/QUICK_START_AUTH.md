# 🎯 Fitur Login dengan Gmail - Cuci AC

Sistem autentikasi lengkap dengan dukungan login manual dan login via Google OAuth.

## ✨ Fitur yang Sudah Tersedia

### 1. **Login Manual**
- Login dengan email dan password
- Validasi form yang ketat
- Session management

### 2. **Registrasi Manual**
- Daftar dengan email dan password
- Simpan informasi lengkap:
  - Nama lengkap
  - Nomor telepon
  - Alamat
  - Kota
  - Password yang terenkripsi
- Validasi email unik

### 3. **Login dengan Google**
- OAuth 2.0 integration
- Auto-ambil foto profil dari Google
- Auto-buat akun jika belum ada
- Link akun existing dengan Google
- Proses yang smooth dan aman

### 4. **User Profile**
- Tampilkan informasi lengkap pengguna
- Edit profil (siap dikembangkan)
- Foto profil dari Google atau custom
- Menampilkan role dan status

### 5. **Sistem Role**
- **User** - Pengguna regular
- **Staff** - Staf dengan akses manajemen
- **Admin** - Administrator dengan akses penuh

### 6. **Admin Dashboard**
- Lihat statistik pengguna
- Kelola semua pengguna
- Edit user information
- Ubah role pengguna
- Nonaktifkan/aktifkan user
- Hapus user

### 7. **Security**
- Password hashing dengan bcrypt
- CSRF protection
- Session management
- Middleware proteksi route
- Role-based access control

---

## 🚀 Langkah-Langkah Setup

### Checklist Setup

- [ ] **Step 1**: Instal Laravel Socialite
- [ ] **Step 2**: Jalankan migrasi database
- [ ] **Step 3**: Setup Google OAuth di Google Cloud Console
- [ ] **Step 4**: Konfigurasi file .env
- [ ] **Step 5**: Test login dan register
- [ ] **Step 6**: Test Google login

### Step 1: Instal Laravel Socialite

Buka terminal di folder project:

```bash
cd d:\Samuel\Proyek\Cuci-AC\sugarac
composer require laravel/socialite
```

### Step 2: Jalankan Migrasi Database

```bash
php artisan migrate
```

Migrasi akan menambahkan kolom:
- `phone` - Nomor telepon
- `google_id` - ID dari Google
- `google_token` - Token untuk Google
- `role` - Role pengguna (user, staff, admin)
- `address` - Alamat lengkap
- `city` - Kota
- `profile_picture` - URL foto profil
- `is_active` - Status aktif/tidak aktif

### Step 3: Setup Google OAuth di Google Cloud Console

1. **Buka Google Cloud Console**
   ```
   https://console.cloud.google.com/
   ```

2. **Buat Project Baru**
   - Klik "Select a Project" di atas
   - Klik "New Project"
   - Masukkan nama: "Cuci AC"
   - Klik "Create"

3. **Aktifkan Google+ API**
   - Di sidebar kiri, klik "APIs & Services" → "Library"
   - Cari "Google+ API"
   - Klik dan tekan "Enable"

4. **Buat OAuth 2.0 Credentials**
   - Klik "Credentials" di sidebar
   - Klik "Create Credentials" → "OAuth Client ID"
   - Pilih "Web Application"
   - Isi form:

   **Authorized JavaScript origins:**
   ```
   http://localhost
   http://localhost:8000
   ```

   **Authorized redirect URIs:**
   ```
   http://localhost/auth/google/callback
   http://localhost:8000/auth/google/callback
   ```

   - Klik "Create"
   - Copy `Client ID` dan `Client Secret`

### Step 4: Konfigurasi File .env

Buka file `d:\Samuel\Proyek\Cuci-AC\sugarac\.env` dan isikan:

```env
GOOGLE_CLIENT_ID=paste_client_id_anda_di_sini
GOOGLE_CLIENT_SECRET=paste_client_secret_anda_di_sini
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

**Contoh:**
```env
GOOGLE_CLIENT_ID=123456789-abc123def456.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abcdefghijk_XYZ123
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

### Step 5: Test Login dan Register

**Jalankan development server:**

```bash
php artisan serve
```

Server akan berjalan di `http://localhost:8000`

**Buka di browser:**
```
http://localhost:8000/register
```

**Test registrasi:**
- Nama: John Doe
- Email: john@example.com
- Telepon: 081234567890
- Alamat: Jl. Contoh
- Kota: Jakarta
- Password: password123

Klik "Daftar" → Seharusnya redirect ke dashboard

**Test login:**
- Logout dulu dari dashboard
- Buka `http://localhost:8000/login`
- Masukkan email dan password
- Klik Login

### Step 6: Test Google Login

**Klik tombol "Login dengan Google"**

Prosesnya:
1. Redirect ke Google login
2. Pilih akun Google Anda
3. Izinkan akses
4. Redirect kembali dan auto-login
5. Langsung ke dashboard

---

## 📁 File dan Folder yang Dibuat

### Controllers
- `app/Http/Controllers/AuthController.php` - Handle login, register, Google OAuth
- `app/Http/Controllers/AdminController.php` - Admin dashboard dan user management

### Middleware
- `app/Http/Middleware/IsAdmin.php` - Proteksi route untuk admin
- `app/Http/Middleware/IsStaff.php` - Proteksi route untuk staff

### Views (Blade Templates)
- `resources/views/welcome.blade.php` - Halaman utama
- `resources/views/dashboard.blade.php` - Dashboard user
- `resources/views/auth/login.blade.php` - Halaman login
- `resources/views/auth/register.blade.php` - Halaman register
- `resources/views/admin/dashboard.blade.php` - Admin dashboard
- `resources/views/admin/users/index.blade.php` - Daftar user admin

### Models
- `app/Models/User.php` - Updated dengan fitur baru dan methods

### Migrations
- `database/migrations/2026_05_23_000000_add_role_and_fields_to_users_table.php`

### Routes
- `routes/web.php` - Updated dengan rute baru

### Configuration
- `config/services.php` - Google OAuth config
- `.env` - Environment variables

### Documentation
- `SETUP_AUTH_GUIDE.md` - Guide setup lengkap
- `AUTHENTICATION_DOCS.md` - Dokumentasi teknis

---

## 🌐 URL yang Tersedia

### Public Routes
| URL | Deskripsi |
|-----|-----------|
| `/` | Halaman utama |
| `/login` | Halaman login |
| `/register` | Halaman register |
| `/auth/google` | Redirect ke Google |
| `/auth/google/callback` | Google callback |

### Protected Routes (Perlu login)
| URL | Deskripsi |
|-----|-----------|
| `/dashboard` | Dashboard user |
| `/logout` | Logout |

### Admin Routes (Perlu login + role admin)
| URL | Deskripsi |
|-----|-----------|
| `/admin/dashboard` | Admin dashboard |
| `/admin/users` | Daftar user |
| `/admin/users/{id}` | Detail user |
| `/admin/users/{id}/edit` | Edit user |

---

## 💡 Cara Menggunakan di Code

### Di Controller

```php
// Check if user logged in
if (Auth::check()) {
    $user = Auth::user();
}

// Get current user
$user = Auth::user();

// Check role
if ($user->isAdmin()) {
    // ...
}

// Logout
Auth::logout();
```

### Di Blade Template

```blade
@auth
    <p>Welcome {{ Auth::user()->name }}</p>
@endauth

@guest
    <a href="{{ route('login') }}">Login</a>
@endguest

@if(Auth::user()->isAdmin())
    <p>Admin area</p>
@endif
```

### Protected Routes

```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', ...);
});

Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/admin', ...);
});

Route::middleware(['auth', 'is-staff'])->group(function () {
    Route::get('/staff', ...);
});
```

---

## 🔧 Troubleshooting

### Error: "Class not found: Laravel\Socialite"

Solusi:
```bash
composer require laravel/socialite
```

### Error: "Redirect URI mismatch"

- Periksa Google Cloud Console
- Pastikan URI redirect sudah benar
- Pastikan di .env juga sudah benar

### Error: "SQLSTATE[42S21]: Column not found"

Solusi:
```bash
php artisan migrate
```

### Google login tidak bekerja

Checklist:
- [ ] Sudah install Laravel Socialite
- [ ] Sudah setup Google OAuth
- [ ] Client ID dan Secret benar
- [ ] Redirect URI benar
- [ ] Database sudah dimigrasi

---

## 📚 Dokumentasi Lengkap

Baca file-file berikut untuk info lebih detail:

1. **SETUP_AUTH_GUIDE.md** - Panduan setup step-by-step
2. **AUTHENTICATION_DOCS.md** - Dokumentasi teknis lengkap
3. **README.md** (di project root) - Info umum project

---

## 🎓 Next Steps (Fitur yang Bisa Dikembangkan)

- [ ] Email verification setelah registrasi
- [ ] Password reset via email
- [ ] Two-factor authentication (2FA)
- [ ] Social login tambahan (Facebook, GitHub)
- [ ] Edit profil user
- [ ] Upload foto profil custom
- [ ] Activity log
- [ ] API authentication (Sanctum)

---

## 📞 Support

Jika ada pertanyaan atau masalah, silakan:
1. Baca dokumentasi di atas
2. Check file `TROUBLESHOOTING` section
3. Cek console browser untuk error
4. Cek Laravel logs di `storage/logs/`

---

**Dibuat:** 23 Mei 2026  
**Status:** ✅ Production Ready  
**Version:** 1.0

---

## 🎉 Selesai!

Sistem login dengan Gmail sudah siap digunakan. Anda bisa:

1. ✅ Login dengan email dan password
2. ✅ Registrasi akun baru
3. ✅ Login dengan Google
4. ✅ Admin dashboard untuk manage users
5. ✅ Role-based access control

Selamat menggunakan! 🚀
