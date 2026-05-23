# Admin Work Management Guide

## Fitur Baru: Tampilan Manajemen Pekerjaan Admin

Tampilan baru ini memungkinkan admin untuk mengelola dan memantau pekerjaan yang telah di-assign ke pekerja.

### Lokasi
- **URL:** `/admin/work-management`
- **Route:** `admin.work-management`
- **Akses:** Hanya untuk admin yang telah login

### Fitur Utama

#### 1. **Statistik Dashboard**
Menampilkan ringkasan pekerjaan dalam bentuk kartu informasi:
- **Total Pekerjaan:** Jumlah semua pesanan yang telah di-assign
- **Menunggu Mulai:** Pesanan yang sudah di-assign tapi belum dimulai
- **Sedang Dikerjakan:** Pesanan yang sedang dalam proses
- **Selesai:** Pesanan yang sudah selesai dikerjakan
- **Pekerja Aktif:** Jumlah pekerja yang memiliki pesanan yang di-assign

#### 2. **Tab Navigasi**
Tiga tab untuk memfilter pekerjaan berdasarkan status:

**Tab 1: Menunggu Mulai**
- Menampilkan semua pesanan yang sudah di-assign tapi belum dimulai
- Tombol "Mulai" untuk memulai pekerjaan
- Informasi lengkap: ID pesanan, nama pelanggan, pekerja yang ditugaskan, layanan, jadwal, dan harga

**Tab 2: Sedang Dikerjakan**
- Menampilkan pesanan yang sedang dalam proses
- List pekerja yang sedang mengerjakan pesanan
- Monitoring real-time pekerjaan

**Tab 3: Selesai**
- Menampilkan pesanan yang sudah selesai dikerjakan
- Riwayat lengkap pekerjaan yang telah diselesaikan
- Informasi tanggal selesai dan total harga

#### 3. **Pekerja Aktif**
Kartu menampilkan semua pekerja yang memiliki pesanan:
- Nama pekerja
- Email
- Total pekerjaan
- Pekerjaan sedang dikerjakan
- Pekerjaan selesai
- Status (Aktif/Tidak Aktif)

#### 4. **Aktivitas Terbaru**
Menampilkan 10 aktivitas terbaru dari semua pesanan yang di-assign:
- Perubahan status pesanan
- Waktu perubahan
- Detail pesanan dan pekerja

### Cara Menggunakan

#### Mulai Pekerjaan
1. Buka tab "Menunggu Mulai"
2. Lihat daftar pesanan yang siap untuk dimulai
3. Klik tombol "Mulai" pada pesanan yang ingin dimulai
4. Konfirmasi dalam modal dialog
5. Status pesanan akan berubah menjadi "Sedang Dikerjakan"

#### Pantau Pekerjaan
1. Lihat statistik dashboard untuk overview
2. Tab "Sedang Dikerjakan" menunjukkan pekerjaan aktif
3. Lihat "Pekerja Aktif" untuk melihat beban kerja setiap pekerja
4. Pantau "Aktivitas Terbaru" untuk perubahan status

#### Lihat Detail Pesanan
1. Klik tombol "Lihat" pada setiap pesanan
2. Akan membuka halaman detail pesanan dengan informasi lengkap

### Integrasi dengan Staff Dashboard

- Pekerja dapat melihat pesanan mereka di Staff Dashboard (`/staff-dashboard`)
- Admin dapat memantau progress dari halaman Work Management ini
- Sistem status:
  - `pending` = Menunggu Mulai
  - `confirmed` = Sedang Dikerjakan  
  - `completed` = Selesai

### Status Pesanan

| Status | Keterangan | Tombol Tersedia |
|--------|-----------|-----------------|
| Pending | Pesanan sudah di-assign tapi belum dimulai | Mulai |
| Confirmed | Pekerjaan sedang dikerjakan | Lihat |
| Completed | Pekerjaan sudah selesai | Lihat |

### Akses dari Menu

Admin dapat mengakses Work Management dari:
1. **Admin Dashboard** - Tombol "Mulai Kelola Pekerjaan"
2. **Direct URL** - `/admin/work-management`
3. **Route Helper** - `route('admin.work-management')`

### Catatan

- Hanya pesanan dengan `assigned_staff_id` yang tidak NULL yang akan ditampilkan
- Pesanan diurutkan berdasarkan `visit_date` (tanggal kunjungan)
- Aktivitas terbaru diurutkan berdasarkan `updated_at` paling terbaru
- Pekerja aktif dihitung dari user yang memiliki assigned orders
