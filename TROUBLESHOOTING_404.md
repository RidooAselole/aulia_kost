# 🔍 Troubleshooting Error 404 pada Admin Login

## Penyebab & Solusi

### 1️⃣ Jika Menggunakan XAMPP Apache

**Masalah**: mod_rewrite belum diaktifkan

**Solusi**:
1. Buka `C:\xampp\apache\conf\httpd.conf`
2. Cari baris: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Hapus tanda `#` di awal:
   ```
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
4. Cari baris: `<Directory "C:/xampp/htdocs">`
5. Pastikan menggunakan `AllowOverride All`:
   ```
   <Directory "C:/xampp/htdocs">
       Options Indexes FollowSymLinks
       AllowOverride All
       Require all granted
   </Directory>
   ```
6. Restart Apache di XAMPP Control Panel
7. Test akses: `http://localhost/aulia_kost/public/admin/login`

---

### 2️⃣ Jika Menggunakan PHP Built-in Server

**Masalah**: Server tidak ter-restart setelah cache clear

**Solusi**:
```bash
# 1. Kill existing server
php artisan serve:stop

# 2. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 3. Start server baru
php artisan serve --port=8000

# 4. Test: http://localhost:8000/admin/login
```

---

### 3️⃣ Jika Masalah Persisten

**Cek ini**:

1. **Routes OK?**
   ```bash
   php artisan route:list | grep admin
   ```
   Should show admin login, dashboard routes

2. **Controller ada?**
   ```bash
   ls -la app/Http/Controllers/AdminController.php
   ```

3. **View ada?**
   ```bash
   ls -la resources/views/admin/login.blade.php
   ```

4. **Check Laravel logs**:
   ```bash
   tail -n 50 storage/logs/laravel.log
   ```

5. **Check error pada deployment**:
   - Buka DevTools (F12) → Console tab
   - Buka DevTools → Network tab, check response dari `/admin/login`

---

## Quick Checklist

- [ ] Mod_rewrite diaktifkan (jika pakai Apache)
- [ ] AllowOverride All diaktifkan di .htaccess
- [ ] Cache sudah di-clear
- [ ] Server sudah di-restart
- [ ] Routes sudah registered (`php artisan route:list`)
- [ ] Controller AdminController.php ada
- [ ] View admin/login.blade.php ada

---

## Akses Login

Setelah 404 fixed:
- **URL**: http://localhost/aulia_kost/public/admin/login
- **Username**: `admin`
- **Password**: `admin`

