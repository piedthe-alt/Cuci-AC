# 📋 Dokumentasi Sistem Pemesanan Cuci AC

## ✅ Yang Sudah Dibuat

### 1. **Database Structure**
- ✅ Table `ac_models` - Menyimpan jenis-jenis model AC (Samsung, LG, Daikin, dll)
- ✅ Table `service_types` - Menyimpan jenis layanan dengan harga per unit
- ✅ Table `orders` - Menyimpan detail pesanan user

### 2. **Models**
- ✅ `AcModel` - Model untuk jenis AC
- ✅ `ServiceType` - Model untuk jenis layanan
- ✅ `Order` - Model untuk pesanan dengan relationships

### 3. **Controllers**
- ✅ `OrderController` - CRUD pesanan user
- ✅ `AcModelController` - Manage AC models (Admin)
- ✅ `ServiceTypeController` - Manage layanan (Admin)
- ✅ `OrderPolicy` - Authorization untuk Order

### 4. **User Dashboard Views**
- ✅ `orders/index.blade.php` - Tabel riwayat pesanan
- ✅ `orders/create.blade.php` - Form pembuatan pesanan dengan:
  - Pilihan Model AC
  - Pilihan Jenis Layanan dengan harga
  - Input jumlah unit
  - Nomor telepon
  - Tanggal & jam kunjungan
  - Input alamat
  - **Geolocation dengan Google Maps** untuk deteksi lokasi
  - Catatan opsional
  - **Estimasi biaya otomatis**
- ✅ `orders/show.blade.php` - Detail pesanan lengkap
- ✅ `orders/edit.blade.php` - Edit pesanan pending

### 5. **Admin Management Views**
- ✅ `admin/ac-models/` - CRUD untuk Model AC
  - index.blade.php - List model AC
  - create.blade.php - Form tambah model
  - edit.blade.php - Form edit model
  - show.blade.php - Detail model dengan statistik pesanan

- ✅ `admin/service-types/` - CRUD untuk Jenis Layanan
  - index.blade.php - List layanan
  - create.blade.php - Form tambah layanan
  - edit.blade.php - Form edit layanan
  - show.blade.php - Detail layanan dengan statistik

### 6. **Layout & Navigation**
- ✅ `layouts/app.blade.php` - Master layout dengan:
  - Top navigation dengan user menu
  - Sidebar admin (tersembunyi untuk regular user)
  - Responsive design
- ✅ `dashboard.blade.php` - Dashboard user dengan tabel pesanan terbaru

### 7. **Routes**
- ✅ Pesanan: `/orders` (index, create, store, show, edit, update, destroy)
- ✅ Admin AC Models: `/admin/ac-models` (resource routes)
- ✅ Admin Layanan: `/admin/service-types` (resource routes)

### 8. **Data Seeding**
- ✅ 8 Model AC contoh (Samsung, LG, Daikin, Panasonic, Toshiba, Sharp, Mitsubishi, Fujitsu)
- ✅ 5 Jenis Layanan contoh dengan harga (Rp 150.000 - Rp 550.000)

---

## 🎯 Fitur Utama

### Untuk User:
1. **Dashboard dengan Pesanan Terbaru** - Lihat 5 pesanan terakhir langsung di dashboard
2. **Buat Pesanan Baru** dengan:
   - Pilihan AC dan layanan dinamis dari database admin
   - Kalkulator harga otomatis (Harga × Jumlah Unit)
   - Deteksi lokasi GPS/Geolocation
   - Simpan koordinat untuk mapping
3. **Kelola Pesanan** - Edit hanya pesanan yang masih pending
4. **Riwayat Pesanan** - Lihat semua pesanan dengan status
5. **Detail Pesanan** - Informasi lengkap + link ke Google Maps

### Untuk Admin:
1. **Kelola Model AC** - Tambah, edit, hapus model AC
2. **Kelola Jenis Layanan** - Manage layanan dan harga
3. **Dashboard Admin** - Overview statistik

---

## 🚀 Cara Menggunakan

### Setup Awal:
```bash
# Sudah selesai! Migrations dan seeding sudah dijalankan
php artisan migrate
php artisan db:seed
```

### User Flow:
1. User login → Dashboard
2. Klik "Buat Pesanan Cuci AC"
3. Isi form dengan detail
4. Sistem otomatis hitung total harga
5. Klik "Buat Pesanan"
6. Pesanan muncul di dashboard dengan status "Menunggu Konfirmasi"

### Admin Flow:
1. Login dengan akun admin
2. Sidebar otomatis muncul
3. Kelola Model AC di `/admin/ac-models`
4. Kelola Jenis Layanan di `/admin/service-types`
5. Lihat pesanan di dashboard

---

## 📊 Status Pesanan

- **Pending** 🟡 - Menunggu konfirmasi admin
- **Confirmed** 🔵 - Admin sudah konfirmasi
- **Completed** 🟢 - Layanan sudah selesai
- **Cancelled** 🔴 - Pesanan dibatalkan

---

## 💾 Data yang Disimpan

### Setiap Pesanan:
```
- User ID
- Model AC yang dipilih
- Jenis Layanan
- Jumlah Unit AC
- Nomor Telepon Aktif
- Alamat Lengkap
- Latitude & Longitude (dari geolocation)
- Tanggal & Jam Kunjungan
- Catatan (opsional)
- Total Harga (otomatis dihitung)
- Status Pesanan
```

---

## 🔧 Fitur Teknis

✅ **Eloquent ORM** - Database relationships  
✅ **Authorization** - OrderPolicy untuk keamanan  
✅ **Responsive Design** - Mobile-friendly dengan Tailwind CSS  
✅ **Form Validation** - Laravel request validation  
✅ **Geolocation API** - Deteksi lokasi user  
✅ **Dynamic Forms** - Harga otomatis update saat pilihan berubah  
✅ **Pagination** - List pesanan dengan pagination  
✅ **Admin Sidebar** - Navigasi admin yang user-friendly  

---

## 📝 Catatan

- AC Models dan Service Types dapat sepenuhnya dikelola admin
- User tidak bisa edit pesanan yang sudah dikonfirmasi
- Total harga dihitung otomatis: Harga Layanan × Jumlah Unit
- Geolocation memerlukan izin user di browser
- Semua data disimpan dengan timestamps created_at dan updated_at

---

## 🎨 Styling

- **Framework**: Tailwind CSS
- **Icons**: Font Awesome 6.4.0
- **Colors**: 
  - Primary: Blue (gradient)
  - Status: Yellow (pending), Blue (confirmed), Green (completed), Red (cancelled)

---

**Sistem pemesanan cuci AC sudah siap digunakan! 🎉**
