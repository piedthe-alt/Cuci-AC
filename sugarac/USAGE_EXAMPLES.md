# 🎯 Contoh Penggunaan Sistem Autentikasi

File ini berisi contoh-contoh practical untuk menggunakan sistem autentikasi di aplikasi Anda.

---

## 1. Menggunakan Auth di View (Blade Template)

### Cek User Login

```blade
@auth
    <p>User sedang login: {{ Auth::user()->name }}</p>
@endauth

@guest
    <p>User belum login</p>
    <a href="{{ route('login') }}">Login di sini</a>
@endguest
```

### Tampilkan Info User

```blade
<div class="user-profile">
    <h2>{{ Auth::user()->name }}</h2>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>Telepon: {{ Auth::user()->phone ?? 'Belum diisi' }}</p>
    <p>Kota: {{ Auth::user()->city }}</p>
    <p>Alamat: {{ Auth::user()->address ?? 'Belum diisi' }}</p>
    
    @if(Auth::user()->profile_picture)
        <img src="{{ Auth::user()->profile_picture }}" alt="Profil">
    @endif
</div>
```

### Cek Role

```blade
{{-- Hanya admin yang lihat ini --}}
@if(Auth::user()->isAdmin())
    <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
@endif

{{-- Staff dan admin bisa lihat ini --}}
@if(Auth::user()->isStaff() || Auth::user()->isAdmin())
    <a href="{{ route('staff.dashboard') }}">Staff Dashboard</a>
@endif

{{-- User biasa lihat ini --}}
@if(Auth::user()->isUser())
    <a href="{{ route('user.dashboard') }}">My Dashboard</a>
@endif
```

### Logout Button

```blade
@auth
    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
@endauth
```

---

## 2. Menggunakan Auth di Controller

### Basic Usage

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user login
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        // Dapatkan user yang sedang login
        $user = Auth::user();
        
        return view('dashboard', [
            'user' => $user,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
```

### Cek Role di Controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // Method 1: Menggunakan method di model
        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized');
        }
        
        // Method 2: Langsung cek role
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        
        return view('admin.dashboard');
    }
}
```

### Operasi dengan User

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        // Dapatkan user login
        $user = Auth::user();
        
        // Akses field
        echo $user->name;        // Nama
        echo $user->email;       // Email
        echo $user->phone;       // Telepon
        echo $user->address;     // Alamat
        echo $user->city;        // Kota
        echo $user->role;        // Role
        echo $user->is_active;   // Status
        
        return view('profile', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Update user
        $user->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
        ]);
        
        return redirect()->back()->with('success', 'Profil updated');
    }
}
```

---

## 3. Protected Routes dengan Middleware

### Route untuk User Login

```php
<?php

use App\Http\Controllers\DashboardController;

// Route ini hanya bisa diakses user yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
```

### Route untuk Admin Only

```php
<?php

use App\Http\Controllers\AdminController;

// Route ini hanya bisa diakses admin
Route::middleware(['auth', 'is-admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminController::class, 'listUsers'])->name('admin.users');
});
```

### Route untuk Staff

```php
<?php

use App\Http\Controllers\StaffController;

// Route ini bisa diakses staff dan admin
Route::middleware(['auth', 'is-staff'])->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::post('/staff/update', [StaffController::class, 'update'])->name('staff.update');
});
```

### Route Guest Only (Login/Register)

```php
<?php

// Route ini hanya untuk user yang belum login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
```

---

## 4. Advanced Usage - Custom Controller

### Membuat Controller untuk Fitur Baru

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order; // Contoh model

class OrderController extends Controller
{
    // Hanya user login yang bisa akses
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $user = Auth::user();
        
        // Hanya ambil order milik user ini
        $orders = Order::where('user_id', $user->id)->get();
        
        return view('orders.index', compact('orders'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Validasi
        $validated = $request->validate([
            'service' => 'required',
            'quantity' => 'required|numeric|min:1',
            'address' => 'required',
        ]);
        
        // Buat order
        $order = Order::create([
            'user_id' => $user->id,
            'service' => $validated['service'],
            'quantity' => $validated['quantity'],
            'address' => $validated['address'] ?? $user->address,
        ]);
        
        return redirect()->back()->with('success', 'Order dibuat');
    }
    
    public function show($id)
    {
        $user = Auth::user();
        $order = Order::findOrFail($id);
        
        // Pastikan order milik user ini
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        return view('orders.show', compact('order'));
    }
}
```

### Register Controller di Routes

```php
<?php

use App\Http\Controllers\OrderController;

Route::middleware('auth')->group(function () {
    Route::resource('orders', OrderController::class);
});

// Routes yang dihasilkan:
// GET    /orders              (index)
// POST   /orders              (store)
// GET    /orders/{id}         (show)
// GET    /orders/{id}/edit    (edit)
// PUT    /orders/{id}         (update)
// DELETE /orders/{id}         (destroy)
```

---

## 5. API Usage (Jika pakai Token/Sanctum)

### Login untuk Mendapat Token

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

Response:
```json
{
  "token": "1|AbCdEfGhIjKlMnOpQrStUvWxYz123...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "user"
  }
}
```

### Menggunakan Token

```bash
curl -X GET http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer 1|AbCdEfGhIjKlMnOpQrStUvWxYz123..."
```

### Controller untuk API

```php
<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        
        if (!Auth::attempt($validated)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        
        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Logged out']);
    }
    
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }
}
```

---

## 6. Testing Authentication

### Test dengan PHPUnit

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
        
        $response->assertRedirect('/dashboard');
    }
    
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }
    
    public function test_admin_can_access_admin_panel()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this
            ->actingAs($admin)
            ->get('/admin/dashboard');
        
        $response->assertStatus(200);
    }
    
    public function test_user_cannot_access_admin_panel()
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this
            ->actingAs($user)
            ->get('/admin/dashboard');
        
        $response->assertStatus(403);
    }
}
```

### Jalankan Test

```bash
php artisan test

# Atau specific test
php artisan test tests/Feature/AuthTest.php

# Dengan verbose
php artisan test --verbose
```

---

## 7. Common Issues & Solutions

### Issue 1: Undefined Method isAdmin()

**Penyebab:** User model tidak ter-update  
**Solusi:**
```php
// Pastikan ini ada di User.php
public function isAdmin(): bool
{
    return $this->role === 'admin';
}
```

### Issue 2: Middleware tidak berfungsi

**Penyebab:** Middleware belum terdaftar  
**Solusi:** Pastikan di `bootstrap/app.php`:
```php
$middleware->alias([
    'is-admin' => \App\Http\Middleware\IsAdmin::class,
    'is-staff' => \App\Http\Middleware\IsStaff::class,
]);
```

### Issue 3: Route tidak ditemukan

**Penyebab:** Route belum di-define atau named route salah  
**Solusi:**
```php
// Define route dengan name
Route::get('/login', ...)->name('login');

// Use di blade
<a href="{{ route('login') }}">Login</a>
```

### Issue 4: Session tidak persist

**Penyebab:** SESSION_DRIVER di .env tidak tepat  
**Solusi:** Pastikan di .env:
```env
SESSION_DRIVER=database
# atau
SESSION_DRIVER=file
```

---

## 8. Best Practices

### 1. Selalu Check Auth Sebelum Access User Data

❌ **Salah:**
```php
$user->name; // Bisa error jika user belum login
```

✅ **Benar:**
```php
if (Auth::check()) {
    $user = Auth::user();
    echo $user->name;
}
```

### 2. Gunakan Middleware untuk Route Protection

❌ **Salah:**
```php
Route::get('/dashboard', function() {
    if (!Auth::check()) {
        redirect('/login');
    }
});
```

✅ **Benar:**
```php
Route::middleware('auth')->get('/dashboard', function() {
    // User sudah dijamin login
});
```

### 3. Gunakan Helper Function

```php
// auth()->check()          -> cek apakah login
// auth()->user()           -> dapatkan user
// auth()->id()             -> dapatkan ID user
// Auth::login($user)       -> login user
// Auth::logout()           -> logout
```

### 4. Validasi Input dengan Proper Rules

```php
$validated = $request->validate([
    'email' => 'required|email|unique:users',
    'password' => 'required|min:8|confirmed',
    'phone' => 'nullable|regex:/^[0-9\-\+\(\)\s]*$/',
]);
```

### 5. Hash Password Sebelum Simpan

```php
use Illuminate\Support\Facades\Hash;

$user = User::create([
    'email' => $request->email,
    'password' => Hash::make($request->password), // ✅ Hash
]);
```

---

## 9. Next Steps

Setelah memahami contoh ini, anda bisa:

1. ✅ Membuat fitur profile edit
2. ✅ Membuat fitur reset password
3. ✅ Membuat email verification
4. ✅ Membuat activity log
5. ✅ Membuat social login lain (Facebook, GitHub)
6. ✅ Membuat API authentication
7. ✅ Membuat two-factor authentication

---

**Dibuat:** 23 Mei 2026  
**Versi:** 1.0  
**Status:** Ready to Use

Gunakan contoh-contoh di atas sebagai referensi untuk mengembangkan fitur-fitur baru! 🚀
