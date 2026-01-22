# Migration Files untuk Database (Disimpan untuk Referensi)

## File Migration yang Tersedia

File-file migration berikut sudah dibuat dan disimpan untuk digunakan nanti ketika database sudah siap diimplementasikan:

### 1. `2026_01_22_000626_create_rooms_table.php`
Migration untuk membuat tabel `rooms` dengan kolom:
- `id` (primary key)
- `number` (nomor kamar, contoh: "105-01", "121-01")
- `price` (harga per bulan)
- `status` (enum: 'tersedia' atau 'ditempati')
- `tenant` (nama penyewa, nullable)
- `timestamps` (created_at, updated_at)

### 2. `2026_01_22_000642_create_bookings_table.php`
Migration untuk membuat tabel `bookings` dengan kolom:
- `id` (primary key)
- `name` (nama penyewa)
- `room_number` (nomor kamar yang dibooking)
- `registration_date` (tanggal daftar)
- `payment_status` (enum: 'unpaid' atau 'paid')
- `payment_due` (tenggat pembayaran)
- `notes` (catatan, nullable)
- `timestamps` (created_at, updated_at)

## Cara Menggunakan Migration Files Ini

Ketika sudah siap untuk mengimplementasikan database:

1. **Jalankan migration:**
   ```bash
   php artisan migrate
   ```

2. **Uncomment routes di `routes/web.php`:**
   - Buka file `routes/web.php`
   - Cari bagian yang di-comment untuk CRUD rooms/bookings
   - Uncomment semua route tersebut

3. **Update AdminController:**
   - Uncomment atau tambahkan kembali method-method CRUD yang sudah dibuat sebelumnya
   - Method-method tersebut ada di file backup atau bisa dibuat ulang

4. **Update dashboard view:**
   - Ganti form action dari `#` kembali ke route yang sesuai
   - Hapus `onsubmit` yang mencegah submit form

## Model yang Sudah Dibuat

Model berikut juga sudah dibuat dan siap digunakan:
- `app/Models/Room.php`
- `app/Models/Booking.php`

## Catatan

- Migration files ini **BELUM dijalankan** (database rollback sudah dilakukan)
- Semua kode yang menggunakan database sudah di-disable
- Dashboard saat ini hanya menampilkan tampilan (frontend only)
- Ketika database sudah siap, cukup jalankan migration dan uncomment kode yang sudah dibuat
