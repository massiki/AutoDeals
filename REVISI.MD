# 🔄 Revisi Sistem: Customer Marketplace + Admin CMS

## Perubahan Brief

**Sebelumnya:** Sistem internal management — Customer Portal *out of scope*.

**Sekarang:** 
- **Customer** bisa browsing katalog mobil dan melakukan pembelian (inquiry/order)
- **Admin** sebagai CMS — mengelola konten (mobil, user, inquiry)

---

## Dampak Perubahan — Tidak Terlalu Besar

Sistem yang sudah dibangun sudah **80% siap**. Tidak ada perubahan besar yang perlu direfactor.

---

## ✅ SUDAH SESUAI (Tidak perlu diubah)

| Komponen | Alasan |
|---|---|
| **Auth** (register, login, logout, reset password) | Tetap sama |
| **Role system** (`role` column, `isAdmin()`) | Admin = CMS, customer = pembeli — sudah pas |
| **Car CRUD** (create, edit, delete, photo upload) | Admin sebagai CMS sudah lengkap |
| **User Management** (admin/user/index) | Admin bisa kelola user — sudah jadi |
| **Admin Dashboard** (stats + inventory table) | CMS dashboard sudah jadi |
| **Inquiry model** (Pending → Test Drive → Approved/Rejected) | Tetap sama, ini untuk customer pembeli |
| **Database migrations** | Semua tabel sudah ada (cars, inquiries, users) |
| **Factories + Seeders** | Data dummy sudah siap |

---

## ⚠️ PERLU PERUBAHAN KECIL

| No | Bagian | Perubahan |
|---|---|---|
| 1 | **Login redirect** (`AuthenticatedSessionController`) | Saat ini semua user diarahkan ke `route('dashboard')` (admin). Customer harus diarahkan ke `dashboard.customer` — check `isAdmin()` |
| 2 | **Public car catalog** | Perlu route baru `GET /cars` dan `GET /cars/{car}` — **tanpa auth** (biar calon pembeli lihat mobil sebelum register) |
| 3 | **Customer dashboard** | Dari placeholder jadi halaman yang menampilkan mobil-mobil available + ringkasan |
| 4 | **Inquiry form di halaman detail mobil** | Customer bisa kirim inquiry langsung dari halaman detail car |
| 5 | **My Inquiries page** | Customer bisa lihat status inquiry mereka sendiri |
| 6 | **Admin Inquiry Management** | Admin perlu halaman untuk lihat semua inquiry, ubah status, filter |
| 7 | **Admin Dashboard** | Tambah stat inquiry (total inquiry, pending, test drive hari ini) |
| 8 | **Sidebar customer** | Tambah menu: Dashboard, Cars, My Inquiries |
| 9 | **Sidebar admin** | Tambah menu: Inquiries |

---

## 🆕 ROUTES BARU YANG DIBUTUHKAN

```
# Public (no auth)
GET  /cars                          → Customer\CarController@index     (catalog)
GET  /cars/{car}                    → Customer\CarController@show      (detail + inquiry form)

# Customer (auth)
POST /inquiries                     → Customer\InquiryController@store
GET  /customer/inquiries            → Customer\InquiryController@index (my inquiries)

# Admin (auth + admin)
GET  /admin/inquiries               → Admin\InquiryController@index
GET  /admin/inquiries/{inquiry}     → Admin\InquiryController@show
PATCH /admin/inquiries/{inquiry}    → Admin\InquiryController@update   (change status)
```

---

## 📊 ALUR USER BARU

```
[Public] Browse Catalog (/cars)
       │
       ├── Klik mobil → Detail (/cars/{car})
       │                    │
       │                    └── Isi inquiry → Login/Register → POST /inquiries
       │
[Customer] Login → Customer Dashboard
                    ├── Browse Cars
                    ├── My Inquiries (track status)
                    └── Profile

[Admin] Login → Admin Dashboard
                ├── Car CRUD (CMS)
                ├── Inquiry Management
                ├── User Management
                └── Stats
```

---

## FILE YANG PERLU DIBUAT / DIUBAH

### Buat Baru

| File | Keterangan |
|---|---|
| `app/Http/Controllers/customer/CarController.php` | Catalog + detail car untuk public |
| `app/Http/Controllers/customer/InquiryController.php` | Submit inquiry + my inquiries |
| `app/Http/Controllers/admin/InquiryController.php` | Manage inquiries (index, show, update status) |
| `resources/views/customer/car/index.blade.php` | Halaman katalog mobil |
| `resources/views/customer/car/show.blade.php` | Halaman detail mobil + form inquiry |
| `resources/views/customer/inquiry/index.blade.php` | My Inquiries page |
| `resources/views/admin/inquiry/index.blade.php` | Admin inquiry list |
| `resources/views/admin/inquiry/show.blade.php` | Admin inquiry detail |

### Ubah

| File | Perubahan |
|---|---|
| `app/Http/Controllers/Auth/AuthenticatedSessionController.php` | Redirect based on role (admin → dashboard, customer → dashboard.customer) |
| `app/Http/Controllers/admin/DashboardController.php` | Tambah inquiry stats |
| `resources/views/customer/dashboard.blade.php` | Dari placeholder jadi halaman berisi ringkasan |
| `resources/views/layouts/customer.blade.php` | Tambah menu sidebar |
| `resources/views/layouts/admin.blade.php` | Tambah menu Inquiries di sidebar |
| `routes/web.php` | Tambah semua route baru |

---

## ESTIMASI

**3-4 jam kerja** untuk menyelesaikan semua fitur.
