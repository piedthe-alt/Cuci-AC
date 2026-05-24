# Panduan Fitur Harga Layanan Per Provinsi

## Overview
Sistem ini memungkinkan Anda untuk mengatur harga layanan yang berbeda untuk setiap provinsi. Harga default akan digunakan jika tidak ada harga khusus yang diatur untuk provinsi tertentu.

## Database Structure

### Tabel `provinces`
Menyimpan daftar 38 provinsi Indonesia.

```
id | name | code | created_at | updated_at
```

### Tabel `service_type_regions`
Menyimpan harga khusus untuk kombinasi layanan dan provinsi.

```
id | service_type_id | province_id | price | created_at | updated_at
```

**Unique Constraint**: Kombinasi `(service_type_id, province_id)` harus unik.

## Model dan Relasi

### ServiceType Model
```php
public function regions()
{
    return $this->hasMany(ServiceTypeRegion::class);
}

public function getPriceByProvince($provinceId)
{
    $regionPrice = $this->regions()
        ->where('province_id', $provinceId)
        ->first();
    
    return $regionPrice ? $regionPrice->price : $this->price;
}
```

### ServiceTypeRegion Model
```php
public function serviceType()
{
    return $this->belongsTo(ServiceType::class);
}

public function province()
{
    return $this->belongsTo(Province::class);
}
```

### Province Model
```php
public function serviceTypeRegions()
{
    return $this->hasMany(ServiceTypeRegion::class);
}
```

## Workflow

### 1. Membuat Jenis Layanan Baru

**URL**: `/admin/service-types/create`

#### Langkah:
1. Isi form dasar (Kategori, Nama, Deskripsi, Harga Default)
2. Klik "Tambah Provinsi" untuk menambah harga khusus provinsi
3. Pilih provinsi dan masukkan harga
4. Ulangi untuk provinsi lain (opsional)
5. Klik "Simpan Layanan"

#### Data yang Disimpan:
- `service_types`: record baru dengan harga default
- `service_type_regions`: 0 atau lebih records sesuai provinsi yang dipilih

### 2. Edit Jenis Layanan

**URL**: `/admin/service-types/{id}/edit`

#### Langkah:
1. Ubah data dasar jika diperlukan
2. Ubah harga regional:
   - Klik "X" untuk menghapus provinsi
   - Klik "Tambah Provinsi" untuk menambah provinsi baru
3. Klik "Simpan Perubahan"

#### Behavior:
- Semua regional pricing lama akan dihapus
- Regional pricing baru akan disimpan

### 3. Melihat Detail Layanan

**URL**: `/admin/service-types/{id}`

#### Informasi yang Ditampilkan:
1. **Harga Default**: Harga dasar untuk semua provinsi
2. **Tabel Harga per Daerah**: 
   - Nama Provinsi
   - Harga per Unit untuk provinsi tersebut
   - Selisih dari Harga Default (positif/negatif)

Hanya akan tampil jika ada harga khusus untuk provinsi tertentu.

### 4. List Jenis Layanan

**URL**: `/admin/service-types`

#### Kolom Tambahan:
- **Harga Regional**: Menampilkan jumlah provinsi yang memiliki harga khusus
  - "X daerah" - ada harga khusus
  - "Tidak ada" - menggunakan harga default untuk semua

## Contoh Penggunaan

### Scenario: Layanan "Cuci AC" dengan Harga Bervariasi

**Harga Default**: Rp 120.000

**Harga per Provinsi**:
- DKI Jakarta: Rp 150.000 (+Rp 30.000)
- Jawa Barat: Rp 130.000 (+Rp 10.000)
- Jawa Tengah: Rp 110.000 (-Rp 10.000)

### Data di Database:

**service_types**:
```
id: 1, name: "Cuci AC", price: 120000
```

**service_type_regions**:
```
id: 1, service_type_id: 1, province_id: 11 (DKI), price: 150000
id: 2, service_type_id: 1, province_id: 12 (Jawa Barat), price: 130000
id: 3, service_type_id: 1, province_id: 13 (Jawa Tengah), price: 110000
```

## Usage di Frontend / API

```php
// Mendapatkan harga untuk provinsi tertentu
$serviceType = ServiceType::find($id);
$price = $serviceType->getPriceByProvince($provinceId);
// Akan mengembalikan: harga khusus jika ada, atau harga default

// Mendapatkan semua harga untuk layanan
$serviceType = ServiceType::with('regions.province')->find($id);
foreach ($serviceType->regions as $region) {
    echo $region->province->name . ": Rp " . $region->price;
}
```

## Setup Awal

```bash
# 1. Jalankan migrasi
php artisan migrate

# 2. Seed data provinsi
php artisan db:seed --class=ProvinceSeeder

# 3. Buka browser dan akses:
# http://localhost:8000/admin/service-types
```

## File yang Diubah/Ditambah

**Migrations**:
- `2026_05_24_000007_create_provinces_table.php` - Tabel provinsi
- `2026_05_24_000008_create_service_type_regions_table.php` - Tabel regional pricing

**Models**:
- `app/Models/Province.php` - Model baru
- `app/Models/ServiceTypeRegion.php` - Model baru
- `app/Models/ServiceType.php` - Update: tambah relasi regions() dan method getPriceByProvince()

**Controllers**:
- `app/Http/Controllers/ServiceTypeController.php` - Update: store(), edit(), update() untuk menangani regional pricing

**Views**:
- `resources/views/admin/service-types/create.blade.php` - Form tambah dengan regional pricing
- `resources/views/admin/service-types/edit.blade.php` - Form edit dengan regional pricing
- `resources/views/admin/service-types/index.blade.php` - List dengan kolom regional pricing
- `resources/views/admin/service-types/show.blade.php` - Detail dengan tabel regional pricing

**Seeders**:
- `database/seeders/ProvinceSeeder.php` - Seed 38 provinsi Indonesia

## Notes

1. Regional pricing bersifat **optional**
2. Jika tidak ada harga khusus untuk provinsi, sistem akan menggunakan **harga default**
3. Unique constraint memastikan tidak ada duplikat untuk kombinasi service_type_id dan province_id
4. Form menggunakan JavaScript untuk dynamic rows dan hidden inputs
5. Data diupdate sebelum form disubmit
