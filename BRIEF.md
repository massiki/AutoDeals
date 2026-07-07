# 🚗 Project Brief: AutoDeals — Car Dealership Management System

## Tentang Project
AutoDeals adalah showroom mobil bekas yang memiliki lebih dari **200 unit mobil**. Saat ini seluruh data kendaraan masih dicatat menggunakan spreadsheet sehingga sering terjadi berbagai masalah:

- Mobil yang sudah terjual masih ditawarkan kembali.
- Sulit mengetahui stok kendaraan.
- Riwayat inquiry pelanggan tidak terdokumentasi.
- Tidak ada dashboard untuk melihat performa inventaris.

Klien membutuhkan sebuah **Web-based Internal Management System** untuk mengelola inventaris kendaraan.

## User Stories

Sebagai Admin saya dapat:

- Login dan Logout
- Melihat dashboard
- Melihat statistik kendaraan
- Melihat daftar mobil
- Search & Filter mobil
- Menambah mobil
- Mengubah mobil
- Menghapus mobil
- Melihat detail mobil
- Upload banyak foto
- Mengelola status mobil
- Melihat dan mengelola inquiry pelanggan

## Data Mobil

- Stock Code (Auto Generated)
- Brand
- Model
- Tahun
- Harga
- Kilometer
- Warna
- Transmisi
- Fuel Type
- Engine CC
- Plat Nomor
- Kondisi
- VIN Number
- Deskripsi
- Foto (maks. 10)
- Status

### Status
- Available
- Reserved
- Sold

### Kondisi
- New
- Excellent
- Good
- Fair
- Poor

### Transmisi
- Manual
- Automatic
- CVT

### Fuel Type
- Bensin
- Diesel
- Hybrid
- Electric

## Data Inquiry

- Nama Pembeli
- Nomor HP
- Email
- Tanggal Inquiry
- Harga Penawaran
- Status
- Catatan

Status:
- Pending
- Test Drive
- Approved
- Rejected

## Dashboard

- Total Cars
- Available Cars
- Reserved Cars
- Sold Cars
- Total Inventory Value
- Recent Cars
- Recent Inquiry

## Halaman

- Login
- Dashboard
- Inventory
- Detail Mobil
- Tambah/Edit Mobil
- Inquiry

## Validasi

- Email valid
- Password minimal 8 karakter
- VIN unik
- Stock Code otomatis
- Harga tidak boleh negatif
- Tahun tidak boleh melebihi tahun sekarang

## Out of Scope

- Customer Portal
- Sales Management
- Supplier Management
- Multi Role
- Email Notification
- Export PDF/Excel
- Deployment

## Deliverables

- Source Code Laravel
- Git Repository
- Migration
- Seeder
- Factory
- README
- Dokumentasi Database

## UI Style

- Sidebar
- Dashboard modern
- Tailwind CSS
- Responsive
- Card, Badge, Table, Modal, Pagination
