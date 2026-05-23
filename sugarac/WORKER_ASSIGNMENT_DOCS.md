# Sistem Assignment Pekerja - Dokumentasi

## Gambaran Umum
Sistem ini memungkinkan Admin untuk secara manual mengassign pekerja (staff) ke pesanan pelanggan. Pekerja hanya akan menerima pekerjaan jika telah di-assign oleh admin.

## Fitur Utama

### 1. **Admin - Halaman Assign Pekerja**
- **Route**: `/orders/assignments`
- **Akses**: Hanya Admin
- **Deskripsi**: Menampilkan daftar semua pesanan yang belum di-assign ke pekerja
- **Fitur**:
  - Melihat daftar pesanan yang belum di-assign
  - Statistik pesanan (total, belum di-assign, sudah di-assign)
  - Tombol untuk assign pekerja ke setiap pesanan

### 2. **Admin - Form Assign Pekerja**
- **Route**: `/orders/{id}/assign`
- **Akses**: Hanya Admin
- **Deskripsi**: Form untuk memilih dan mengassign pekerja ke pesanan tertentu
- **Fitur**:
  - Menampilkan detail lengkap pesanan
  - Daftar pekerja aktif yang tersedia
  - Radio button untuk memilih pekerja
  - Tombol untuk confirm assignment

### 3. **Staff - Dashboard**
- **Route**: `/staff-dashboard`
- **Akses**: Hanya Staff
- **Deskripsi**: Dashboard untuk pekerja melihat pekerjaan yang sudah di-assign
- **Fitur**:
  - Statistik pekerjaan (total, pending, dikonfirmasi, selesai)
  - Daftar semua pesanan yang di-assign ke pekerja
  - Informasi pelanggan dan detail layanan
  - Tombol untuk melihat detail pesanan
  - Dropdown untuk mengubah status pesanan

## Alur Kerja

```
┌─────────────────────────────────────────────────┐
│  1. Pelanggan membuat pesanan                   │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  2. Pesanan masuk dengan status "pending"       │
│     Pesanan BELUM di-assign ke pekerja          │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  3. Admin membuka halaman "Assign Pekerja"      │
│     /orders/assignments                         │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  4. Admin memilih pesanan dan klik "Assign"     │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  5. Admin memilih pekerja dari daftar           │
│     dan klik "Assign Pekerja"                   │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  6. Pesanan berhasil di-assign!                 │
│     - Status berubah dari "pending" → "confirmed"
│     - assigned_staff_id terisi                  │
│     - assigned_at diisi dengan waktu saat ini   │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  7. Pekerja dapat melihat pesanan di Dashboard  │
│     /staff-dashboard                            │
│     - Melihat semua detail pesanan              │
│     - Melihat info pelanggan                    │
│     - Melihat jadwal kunjungan                  │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│  8. Pekerja mengubah status:                    │
│     "pending" → "confirmed" → "completed"       │
└─────────────────────────────────────────────────┘
```

## Database Schema

### Tabel `orders` - Kolom Baru
```sql
- assigned_staff_id (nullable, foreign key ke users)
- assigned_at (nullable, timestamp)
```

### Foreign Key
- `assigned_staff_id` → `users.id` (one-to-many)
- OnDelete: `SET NULL` (jika staff dihapus, assignment tetap ada tapi staff_id jadi NULL)

## Model Relationships

### Order Model
```php
// Relasi untuk mendapatkan user yang order
public function user()
{
    return $this->belongsTo(User::class);
}

// Relasi untuk mendapatkan staff yang di-assign
public function assignedStaff()
{
    return $this->belongsTo(User::class, 'assigned_staff_id');
}
```

### Scopes di Order Model
```php
// Mendapatkan order yang belum di-assign
Order::unassigned()

// Mendapatkan order yang sudah di-assign
Order::assigned()

// Mendapatkan order untuk staff tertentu
Order::assignedTo($staffId)

// Mendapatkan order untuk user tertentu
Order::ofUser($userId)
```

## Middleware & Authorization

### OrderPolicy
- **view()**: Bisa diakses oleh: user pemilik pesanan, admin, atau staff yang di-assign
- **update()**: Hanya user pemilik pesanan yang belum dikonfirmasi
- **delete()**: Hanya user pemilik pesanan yang belum dikonfirmasi

## Routes

### Admin Routes
```
GET  /orders/assignments              - Lihat daftar pesanan belum di-assign
GET  /orders/{id}/assign              - Form assign pekerja
POST /orders/{id}/assign              - Submit assignment
```

### Staff Routes
```
GET  /staff-dashboard                 - Dashboard pekerja
POST /orders/{id}/status              - Update status pesanan
```

## Roles & Permissions

### Admin
- ✅ Lihat halaman assignment
- ✅ Assign pekerja ke pesanan
- ✅ Lihat semua pesanan

### Staff
- ✅ Lihat pesanan yang di-assign ke mereka
- ✅ Update status pesanan (dari pending → confirmed → completed)
- ❌ Lihat pesanan yang tidak di-assign ke mereka
- ❌ Assign pesanan ke diri sendiri

### Regular User (Pelanggan)
- ✅ Membuat pesanan
- ✅ Lihat pesanan mereka sendiri
- ✅ Edit pesanan yang masih pending
- ✅ Batalkan pesanan yang masih pending

## Testing

### Scenario 1: Admin Assign Pesanan
1. Login sebagai Admin
2. Buka menu "Assign Pekerja" di sidebar
3. Lihat daftar pesanan yang belum di-assign
4. Klik tombol "Assign" pada pesanan
5. Pilih pekerja dari dropdown
6. Klik "Assign Pekerja"
7. Seharusnya melihat pesan sukses

### Scenario 2: Staff Melihat Pekerjaan
1. Login sebagai Staff
2. Seharusnya melihat sidebar "Dashboard Pekerja"
3. Buka "/staff-dashboard"
4. Lihat statistik pekerjaan
5. Lihat daftar pesanan yang di-assign
6. Klik "Detail" untuk melihat detail pesanan
7. Update status pesanan menjadi "completed"

### Scenario 3: Pesanan Tanpa Assignment
1. Login sebagai Customer
2. Buat pesanan baru
3. Pesanan akan memiliki `assigned_staff_id = NULL`
4. Staff tidak akan bisa melihat pesanan ini
5. Hanya setelah admin assign, staff bisa melihat

## API Response Example

### Get Unassigned Orders
```json
{
  "orders": [
    {
      "id": 1,
      "user_id": 2,
      "assigned_staff_id": null,
      "assigned_at": null,
      "status": "pending",
      "visit_date": "2026-05-25 10:00:00",
      "user": {
        "id": 2,
        "name": "John Doe",
        "phone": "08123456789"
      }
    }
  ]
}
```

### Get Assigned Orders for Staff
```json
{
  "orders": [
    {
      "id": 1,
      "user_id": 2,
      "assigned_staff_id": 3,
      "assigned_at": "2026-05-23 15:30:00",
      "status": "confirmed",
      "visit_date": "2026-05-25 10:00:00",
      "user": {
        "id": 2,
        "name": "John Doe"
      },
      "assignedStaff": {
        "id": 3,
        "name": "Ahmad (Staff)"
      }
    }
  ]
}
```

## Files yang Dimodifikasi

1. **Migration**: `2026_05_23_170000_add_assigned_staff_to_orders_table.php`
   - Tambah kolom `assigned_staff_id` dan `assigned_at`

2. **Models**:
   - `app/Models/Order.php` - Tambah relasi, scopes, dan fillable
   - `app/Models/User.php` - (sudah ada relasi, tidak perlu modifikasi)

3. **Controllers**:
   - `app/Http/Controllers/OrderController.php` - Tambah 4 method baru:
     - `staffAssignments()`
     - `showAssignForm()`
     - `assignStaff()`
     - `staffDashboard()`
     - `updateStatus()`

4. **Views**:
   - `resources/views/orders/assignments/index.blade.php` - Daftar pesanan belum di-assign
   - `resources/views/orders/assignments/assign.blade.php` - Form assign
   - `resources/views/orders/staff-dashboard.blade.php` - Dashboard pekerja
   - `resources/views/admin/dashboard.blade.php` - Tambah link assignment
   - `resources/views/layouts/app.blade.php` - Tambah staff sidebar

5. **Routes**:
   - `routes/web.php` - Tambah routes untuk assignment dan staff dashboard

6. **Policies**:
   - `app/Policies/OrderPolicy.php` - Update `view()` method untuk staff

## Catatan Penting

- Admin HARUS manually assign pekerja sebelum pekerja bisa melihat pesanan
- Status pesanan otomatis berubah ke "confirmed" saat di-assign
- Jika staff dihapus dari sistem, `assigned_staff_id` akan menjadi NULL
- Staff hanya bisa melihat pesanan yang di-assign ke mereka
- Admin bisa melihat semua pesanan

## Next Steps (Optional Enhancements)

- [ ] Email notification ketika pesanan di-assign
- [ ] SMS notification ke pekerja
- [ ] Rating system untuk evaluasi pekerja
- [ ] Assignment history & audit log
- [ ] Workload distribution feature
- [ ] Map view untuk lokasi pesanan
