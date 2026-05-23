# Setup Guide - Fitur Login dengan Gmail

Panduan lengkap untuk mengaktifkan fitur login dengan Gmail di aplikasi Cuci AC.

## 1. Instalasi Laravel Socialite

Jalankan perintah berikut untuk menginstal Laravel Socialite:

```bash
composer require laravel/socialite
```

## 2. Konfigurasi Google OAuth

### 2.1 Buat Kredensial Google Cloud

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau gunakan project yang sudah ada
3. Aktifkan Google+ API
4. Buat OAuth 2.0 Client ID (Tipe: Web Application)
5. Tambahkan URI yang diizinkan:
   - Authorized JavaScript origins: `http://localhost`
   - Authorized redirect URIs: `http://localhost/auth/google/callback`

### 2.2 Konfigurasi File .env

Buka file `.env` dan isi kredensial Google Anda:

```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URI=http://localhost/auth/google/callback
```

## 3. Jalankan Migrasi

Untuk membuat kolom baru di database (role, phone, address, dll):

```bash
php artisan migrate
```

## 4. Struktur Folder Baru

Fitur ini telah membuat beberapa folder dan file baru:

```
- app/Http/Controllers/AuthController.php       (Controller untuk autentikasi)
- resources/views/auth/login.blade.php           (Halaman login)
- resources/views/auth/register.blade.php        (Halaman register)
- resources/views/dashboard.blade.php            (Dashboard pengguna)
- database/migrations/2026_05_23_*.php           (Migrasi database)
```

## 5. Route yang Tersedia

### Route Publik (Tanpa Login)
- `GET /` - Halaman utama
- `GET /login` - Halaman login
- `POST /login` - Proses login
- `GET /register` - Halaman register
- `POST /register` - Proses registrasi
- `GET /auth/google` - Redirect ke Google OAuth
- `GET /auth/google/callback` - Callback dari Google OAuth

### Route Terlindungi (Memerlukan Login)
- `GET /dashboard` - Dashboard pengguna
- `POST /logout` - Proses logout

## 6. Fitur Pengguna

### Informasi yang Disimpan
- Nama lengkap
- Email
- Password (jika registrasi manual)
- Nomor telepon
- Alamat
- Kota
- Foto profil (dari Google)
- Role (user, staff, admin)
- Status aktif/tidak aktif

### Role Pengguna
1. **User** - Pengguna regular yang baru terdaftar
2. **Staff** - Staf yang dapat mengelola data
3. **Admin** - Administrator dengan akses penuh

### Method di Model User
```php
$user->isAdmin()    // Cek apakah admin
$user->isStaff()    // Cek apakah staff
$user->isUser()     // Cek apakah user biasa
```

## 7. Menggunakan Autentikasi di View

### Cek User Login
```blade
@auth
    <p>User login: {{ Auth::user()->name }}</p>
@endauth

@guest
    <p>Silakan login terlebih dahulu</p>
@endguest
```

### Cek Role
```blade
@if(Auth::user()->isAdmin())
    <p>Ini adalah halaman admin</p>
@endif

@if(Auth::user()->isStaff())
    <p>Ini adalah halaman staff</p>
@endif
```

## 8. Menggunakan Autentikasi di Controller

```php
// Cek apakah user login
if (Auth::check()) {
    $user = Auth::user();
}

// Dapatkan user yang sedang login
$user = Auth::user();

// Login user
Auth::login($user);

// Logout user
Auth::logout();

// Cek role
if ($user->isAdmin()) {
    // Lakukan sesuatu
}
```

## 9. Middleware untuk Proteksi Route

### Gunakan middleware 'auth' untuk melindungi route
```php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
});
```

### Gunakan middleware 'guest' untuk route login/register
```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::get('/register', [AuthController::class, 'showRegister']);
});
```

## 10. Membuat Halaman Admin

Untuk melindungi halaman hanya untuk admin, buat middleware baru:

```bash
php artisan make:middleware IsAdmin
```

Update middleware:
```php
public function handle($request, Closure $next)
{
    if (!auth()->check() || !auth()->user()->isAdmin()) {
        abort(403);
    }
    return $next($request);
}
```

Gunakan di route:
```php
Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
});
```

## 11. Troubleshooting

### Error: "Class not found: Laravel\Socialite"
Pastikan Anda sudah menjalankan `composer require laravel/socialite`

### Error: "Redirect URI mismatch"
Pastikan GOOGLE_REDIRECT_URI di .env cocok dengan yang terdaftar di Google Cloud Console

### Error: "SQLSTATE[42S21]: Column not found"
Jalankan `php artisan migrate` untuk membuat kolom baru di database

### Google Login tidak bekerja
1. Pastikan Anda sudah membuat OAuth 2.0 Client ID
2. Pastikan URI redirect sudah benar
3. Cek .env file untuk kredensial yang tepat

## 12. Fitur Tambahan yang Bisa Diimplementasikan

- [ ] Edit profil pengguna
- [ ] Unggah foto profil
- [ ] Verifikasi email
- [ ] Password reset via email
- [ ] Two-factor authentication
- [ ] Social login lainnya (Facebook, GitHub, dll)
- [ ] Dashboard admin untuk kelola users
- [ ] Sistem role dan permission yang lebih kompleks

---

**Dibuat**: 23 Mei 2026
**Aplikasi**: Cuci AC - Sistem Manajemen Layanan Cuci AC
