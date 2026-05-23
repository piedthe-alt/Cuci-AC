# Dokumentasi Sistem Autentikasi - Cuci AC

## 📋 Daftar Isi
1. [Instalasi](#instalasi)
2. [Konfigurasi](#konfigurasi)
3. [Fitur](#fitur)
4. [Penggunaan](#penggunaan)
5. [API Reference](#api-reference)
6. [Troubleshooting](#troubleshooting)

---

## Instalasi

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- MySQL 5.7 atau lebih tinggi
- Node.js dan NPM (opsional, untuk frontend)

### Langkah 1: Install Laravel Socialite

```bash
cd d:\Samuel\Proyek\Cuci-AC\sugarac
composer require laravel/socialite
```

### Langkah 2: Jalankan Migrasi

```bash
php artisan migrate
```

Perintah ini akan membuat kolom baru di tabel `users`:
- `phone` - Nomor telepon pengguna
- `google_id` - ID Google untuk OAuth
- `google_token` - Token Google
- `role` - Role pengguna (user, staff, admin)
- `address` - Alamat lengkap
- `city` - Kota tempat tinggal
- `profile_picture` - URL foto profil
- `is_active` - Status aktif/tidak aktif

### Langkah 3: Konfigurasi Environment

Buka file `.env` dan tambahkan konfigurasi Google OAuth:

```env
GOOGLE_CLIENT_ID=your_client_id_from_google_console
GOOGLE_CLIENT_SECRET=your_client_secret_from_google_console
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

---

## Konfigurasi

### Setup Google OAuth di Google Cloud Console

1. **Buka Google Cloud Console**
   - Kunjungi https://console.cloud.google.com/
   - Login dengan akun Google Anda

2. **Buat Project Baru**
   - Klik "Select a Project" → "New Project"
   - Masukkan nama project (misal: "Cuci AC")
   - Klik "Create"

3. **Aktifkan Google+ API**
   - Di sidebar, klik "APIs & Services" → "Library"
   - Cari "Google+ API"
   - Klik dan tekan "Enable"

4. **Buat OAuth 2.0 Credentials**
   - Klik "Credentials" di sidebar
   - Klik "Create Credentials" → "OAuth Client ID"
   - Pilih tipe "Web Application"
   - Tambahkan:
     - **Authorized JavaScript origins**: `http://localhost`
     - **Authorized redirect URIs**: `http://localhost/auth/google/callback`
   - Klik "Create"
   - Copy `Client ID` dan `Client Secret`

5. **Update File .env**
   - Paste `Client ID` dan `Client Secret` ke file `.env`

---

## Fitur

### 1. Login Manual dengan Email

```php
// User bisa login dengan email dan password
// Route: POST /login
```

**Form Input:**
- Email
- Password

**Validasi:**
- Email harus valid
- Password minimal 6 karakter

### 2. Registrasi Manual dengan Email

```php
// User bisa registrasi dengan membuat akun baru
// Route: POST /register
```

**Form Input:**
- Nama lengkap (required)
- Email (required, unique)
- Nomor telepon (optional)
- Alamat (optional)
- Kota (optional)
- Password (required, minimal 8 karakter)
- Konfirmasi password (required)

### 3. Login dengan Google

```php
// User bisa login langsung dengan Google
// Route: GET /auth/google
// Callback: GET /auth/google/callback
```

**Fitur:**
- Ambil foto profil dari Google
- Buat akun otomatis jika belum ada
- Update akun jika sudah ada
- Link akun existing dengan Google

### 4. Dashboard Pengguna

```php
// Setelah login, user diarahkan ke dashboard
// Route: GET /dashboard
```

**Informasi yang Ditampilkan:**
- Nama pengguna
- Email
- Nomor telepon
- Alamat
- Kota
- Foto profil
- Role pengguna
- Status aktif

### 5. Role & Permission

Sistem memiliki 3 role:

| Role | Deskripsi | Akses |
|------|-----------|-------|
| **user** | Pengguna regular | Akses standar |
| **staff** | Staff | Manajemen data |
| **admin** | Administrator | Akses penuh |

---

## Penggunaan

### Di View (Blade Template)

#### 1. Cek User Login

```blade
@auth
    <p>User login: {{ Auth::user()->name }}</p>
@endauth

@guest
    <p>Silakan login terlebih dahulu</p>
@endguest
```

#### 2. Tampilkan Informasi User

```blade
<p>Nama: {{ Auth::user()->name }}</p>
<p>Email: {{ Auth::user()->email }}</p>
<p>Kota: {{ Auth::user()->city }}</p>
<p>Telepon: {{ Auth::user()->phone }}</p>
```

#### 3. Cek Role

```blade
@if(Auth::user()->isAdmin())
    <p>Ini adalah panel admin</p>
@elseif(Auth::user()->isStaff())
    <p>Ini adalah panel staff</p>
@else
    <p>Ini adalah halaman user biasa</p>
@endif
```

#### 4. Tombol Login/Logout

```blade
@guest
    <a href="{{ route('login') }}">Login</a>
    <a href="{{ route('register') }}">Register</a>
@else
    <p>Selamat datang, {{ Auth::user()->name }}</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endguest
```

### Di Controller

#### 1. Cek User Login

```php
if (Auth::check()) {
    $user = Auth::user();
} else {
    // User belum login
}
```

#### 2. Dapatkan User Login

```php
$user = Auth::user();
$name = $user->name;
$email = $user->email;
$role = $user->role;
```

#### 3. Cek Role

```php
if (Auth::user()->isAdmin()) {
    // Akses admin
}

if (Auth::user()->isStaff()) {
    // Akses staff
}

if (Auth::user()->isUser()) {
    // Akses user
}
```

#### 4. Login User

```php
Auth::login($user);
```

#### 5. Logout User

```php
Auth::logout();
```

### Protected Routes

#### Route dengan Middleware Auth

```php
// Hanya user yang login bisa akses
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/profile', [ProfileController::class, 'show']);
});
```

#### Route dengan Middleware Guest (Hanya untuk guest)

```php
// Hanya user yang belum login bisa akses
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);
});
```

#### Route dengan Middleware Admin

```php
// Hanya admin yang bisa akses
Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'listUsers']);
});
```

#### Route dengan Middleware Staff

```php
// Staff dan admin bisa akses
Route::middleware(['auth', 'is-staff'])->group(function () {
    Route::get('/staff', [StaffController::class, 'dashboard']);
    Route::post('/staff/update', [StaffController::class, 'update']);
});
```

---

## API Reference

### AuthController

#### showLogin()
Menampilkan halaman login

```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
```

#### login(Request $request)
Proses login

```php
Route::post('/login', [AuthController::class, 'login']);
```

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

#### showRegister()
Menampilkan halaman registrasi

```php
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
```

#### register(Request $request)
Proses registrasi

```php
Route::post('/register', [AuthController::class, 'register']);
```

**Request Body:**
```json
{
    "name": "Nama User",
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password",
    "phone": "081234567890",
    "address": "Jl. Contoh",
    "city": "Jakarta"
}
```

#### redirectToGoogle()
Redirect ke Google OAuth

```php
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
```

#### handleGoogleCallback()
Handle callback dari Google

```php
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
```

#### logout(Request $request)
Proses logout

```php
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

### User Model Methods

#### isAdmin()
Cek apakah user adalah admin

```php
$user->isAdmin(); // return bool
```

#### isStaff()
Cek apakah user adalah staff

```php
$user->isStaff(); // return bool
```

#### isUser()
Cek apakah user adalah user regular

```php
$user->isUser(); // return bool
```

---

## Troubleshooting

### Error: "Class not found: Laravel\Socialite"

**Solusi:**
```bash
composer require laravel/socialite
```

### Error: "Redirect URI mismatch"

**Penyebab:** URI redirect di .env tidak sama dengan di Google Cloud Console

**Solusi:**
1. Buka Google Cloud Console
2. Periksa "Authorized redirect URIs"
3. Pastikan sama dengan GOOGLE_REDIRECT_URI di .env

### Error: "SQLSTATE[42S21]: Column not found"

**Penyebab:** Belum menjalankan migrasi

**Solusi:**
```bash
php artisan migrate
```

### Google Login tidak bekerja

**Checklist:**
- [ ] Sudah install Laravel Socialite
- [ ] Sudah buat OAuth 2.0 Client ID di Google Cloud Console
- [ ] Client ID dan Secret sudah benar di .env
- [ ] Redirect URI sudah benar
- [ ] Database sudah dimigrasi
- [ ] Cek console browser untuk error JavaScript

### Email sudah terdaftar saat registrasi

**Penyebab:** Email sudah ada di database

**Solusi:**
- Gunakan email lain saat registrasi
- Atau login jika sudah punya akun

### Password tidak cocok saat login

**Penyebab:** Email atau password salah

**Solusi:**
- Pastikan password benar
- Gunakan fitur "lupa password" jika ada
- Atau daftar akun baru

---

## File Struktur

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AuthController.php          # Controller autentikasi
│   └── Middleware/
│       ├── IsAdmin.php                 # Middleware admin
│       └── IsStaff.php                 # Middleware staff
└── Models/
    └── User.php                        # User model

resources/views/
├── welcome.blade.php                   # Halaman utama
├── dashboard.blade.php                 # Dashboard user
└── auth/
    ├── login.blade.php                 # Halaman login
    └── register.blade.php              # Halaman register

database/migrations/
└── 2026_05_23_000000_add_role_and_fields_to_users_table.php

routes/
└── web.php                             # Web routes

config/
└── services.php                        # Konfigurasi services
```

---

## Fitur Tambahan yang Bisa Dikembangkan

- [ ] Email verification
- [ ] Password reset via email
- [ ] Two-factor authentication (2FA)
- [ ] Social login tambahan (Facebook, GitHub, dll)
- [ ] Edit profil user
- [ ] Upload foto profil custom
- [ ] Admin panel untuk manage users
- [ ] Activity log
- [ ] Session management
- [ ] API authentication (Laravel Sanctum)

---

**Dibuat:** 23 Mei 2026  
**Versi:** 1.0  
**Status:** Production Ready

Untuk pertanyaan atau masalah, silakan buat issue atau hubungi tim development.
