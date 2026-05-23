# 🚀 Quick Start Guide - Sistem Assignment Pekerja

## Ringkas Fitur
Sistem yang baru ini memungkinkan **Admin** untuk secara **manual mengassign pekerja** ke pesanan pelanggan. Pekerja hanya akan menerima pekerjaan jika telah di-assign oleh admin.

---

## 📋 Untuk Admin

### Step 1: Akses Halaman Assignment
1. Login sebagai Admin
2. Cari menu **"Assign Pekerja"** di sidebar kiri
3. Atau akses langsung: `/orders/assignments`

### Step 2: Lihat Daftar Pesanan
- Anda akan melihat **daftar semua pesanan yang BELUM di-assign**
- Setiap baris menampilkan:
  - Nomor pesanan
  - Nama pelanggan & nomor telepon
  - Jenis layanan & jumlah unit
  - Tanggal kunjungan
  - Status pesanan
  - Tombol "Assign"

### Step 3: Assign Pekerja
1. Klik tombol **"Assign"** pada pesanan pilihan
2. Anda akan dibawa ke halaman form assignment
3. **Lihat detail pesanan** (customer, layanan, jadwal, dll)
4. **Pilih pekerja** dari daftar yang tersedia
5. Klik **"Assign Pekerja"** untuk confirm
6. ✅ Pesanan sudah di-assign! Status otomatis berubah ke "confirmed"

### Step 4: Monitoring
- Stat box menampilkan:
  - **Total Pesanan**: Jumlah semua pesanan
  - **Belum Di-Assign**: Pesanan yang masih butuh assignment
  - **Sudah Di-Assign**: Pesanan yang sudah di-assign

---

## 👷 Untuk Pekerja (Staff)

### Step 1: Akses Dashboard Pekerja
1. Login sebagai Staff/Pekerja
2. Sidebar akan berubah menjadi **"Dashboard Pekerja"**
3. Klik menu **"Dashboard"** atau **"Pekerjaan Saya"**
4. Atau akses langsung: `/staff-dashboard`

### Step 2: Lihat Pekerjaan Anda
- **Statistik di atas**:
  - Total pekerjaan yang di-assign
  - Pesanan status Pending
  - Pesanan status Dikonfirmasi
  - Pesanan status Selesai

- **Tabel daftar pekerjaan**:
  - ID Pesanan
  - Nama pelanggan & nomor telepon
  - Jenis layanan
  - Lokasi pelanggan
  - Jadwal kunjungan
  - Status saat ini

### Step 3: Lihat Detail Pesanan
1. Klik tombol **"Detail"** pada pesanan
2. Modal akan menampilkan:
   - Data pelanggan lengkap
   - Alamat & lokasi
   - Jadwal kunjungan
   - Dropdown untuk mengubah status

### Step 4: Update Status Pesanan
1. Di dalam modal detail, ada **dropdown "Perbarui Status"**
2. Pilih status:
   - **Dikonfirmasi**: Pesanan sudah dikonfirmasi akan dikerjakan
   - **Selesai**: Pekerjaan sudah selesai
3. Klik **"Simpan"** untuk update
4. ✅ Status pesanan berhasil diupdate!

---

## 📊 Status Pesanan

### Admin Perspective
```
Pesanan Baru
    ↓
Belum Di-Assign (Pesanan ada di halaman assignment)
    ↓
Di-Assign ke Pekerja (Klik Assign → Pilih Staff → Submit)
    ↓
Status berubah → "Confirmed"
```

### Staff Perspective
```
Pesanan Di-Assign ke saya
    ↓
Lihat di Dashboard (Status: Pending/Confirmed)
    ↓
Update Status → "Dikonfirmasi" (saat mulai pekerjaan)
    ↓
Update Status → "Selesai" (saat selesai)
    ↓
Pesanan Completed ✅
```

---

## 🔒 Keamanan & Permissions

| Aksi | Admin | Staff | Customer |
|------|-------|-------|----------|
| Lihat halaman assignment | ✅ | ❌ | ❌ |
| Assign pekerja | ✅ | ❌ | ❌ |
| Lihat pekerjaan yang di-assign | ✅ | ✅ | ❌ |
| Update status pesanan | ❌ | ✅ | ❌ |
| Buat pesanan | ❌ | ❌ | ✅ |
| Lihat pesanan sendiri | ✅ | ❌ | ✅ |

---

## ⚠️ Penting!

1. **Pesanan HANYA akan muncul di dashboard staff setelah di-assign**
   - Jika staff belum di-assign, mereka tidak akan tahu ada pesanan

2. **Admin HARUS assign pekerja secara manual**
   - Sistem ini TIDAK auto-assign
   - Admin punya kontrol penuh

3. **Pekerja hanya bisa lihat pesanan mereka**
   - Pekerja A tidak bisa lihat pekerjaan pekerja B
   - Privasi terjaga ✅

4. **Status otomatis berubah ke "Confirmed" saat di-assign**
   - Admin tidak perlu confirm manual
   - Hemat waktu ⏱️

---

## 🆘 Troubleshooting

### ❓ Saya staff tapi tidak bisa akses /staff-dashboard
- Pastikan akun Anda adalah staff (bukan regular user)
- Admin harus set role Anda menjadi "staff"

### ❓ Saya tidak bisa assign pekerja
- Pastikan Anda login sebagai admin
- Pastikan ada pekerja yang aktif di sistem
- Pekerja harus punya `is_active = true`

### ❓ Pesanan saya tidak muncul di halaman assignment
- Pesanan harus memiliki `assigned_staff_id = NULL`
- Jika sudah di-assign, akan muncul di dashboard staff

### ❓ Saya tidak bisa lihat pesanan customer tertentu
- Jika Anda staff: Pesanan harus di-assign ke Anda
- Jika Anda customer: Hanya bisa lihat pesanan sendiri
- Jika Anda admin: Bisa lihat semua

---

## 📱 Demo Flow

### Skenario: Customer Order → Admin Assign → Staff Works

```
┌─────────────────────┐
│ 1. CUSTOMER         │
│ Buat Pesanan Baru   │
│ Status: PENDING     │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ 2. ADMIN            │
│ Buka Assign Pekerja │
│ Lihat pesanan baru  │
└──────────┬──────────┘
           │
           ▼
┌─────────────────────────┐
│ 3. ADMIN                │
│ Klik "Assign" pesanan   │
│ Pilih pekerja "Ahmad"   │
│ Klik "Assign Pekerja"   │
└──────────┬──────────────┘
           │
           ▼
┌──────────────────────────────┐
│ 4. SISTEM                    │
│ Update pesanan:              │
│ - assigned_staff_id = 3      │
│ - assigned_at = now()        │
│ - status = "confirmed"       │
└──────────┬───────────────────┘
           │
           ▼
┌──────────────────────────────┐
│ 5. AHMAD (STAFF)             │
│ Login ke /staff-dashboard    │
│ Lihat pesanan di list        │
│ Klik "Detail"                │
└──────────┬───────────────────┘
           │
           ▼
┌──────────────────────────────┐
│ 6. AHMAD (STAFF)             │
│ Update status → "Selesai"    │
│ Klik "Simpan"                │
│ Pesanan completed!           │
└──────────────────────────────┘
```

---

## 🎯 Best Practices

✅ **DO:**
- Assign pesanan segera setelah customer order
- Check ketersediaan pekerja sebelum assign
- Update status pesanan secara real-time
- Monitor dashboard untuk pesanan yang tertunda

❌ **DON'T:**
- Jangan assign pesanan yang sudah dikerjakan
- Jangan hapus staff yang masih punya pesanan
- Jangan ubah status pesanan di database langsung
- Jangan share akun staff dengan orang lain

---

## 📞 Kontrol Hubungi

Jika ada pertanyaan atau issue:
1. Check dokumentasi lengkap: `WORKER_ASSIGNMENT_DOCS.md`
2. Lihat file controller: `app/Http/Controllers/OrderController.php`
3. Lihat model: `app/Models/Order.php`

---

**Happy Assignment! 🎉**
