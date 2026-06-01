# 🏢 Portal Lembur IT

[![Laravel Version](https://img.shields.io/badge/Laravel-v11.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-v8.2+-blue.svg)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-v3.x-38bdf8.svg)](https://tailwindcss.com)
[![Docker](https://img.shields.io/badge/Docker-Supported-blue.svg)](https://www.docker.com)

**Portal Lembur IT** (sebelumnya Gas-Lembur) adalah sebuah platform web portal perusahaan premium tingkat tinggi yang dirancang khusus untuk mengelola pengajuan lembur dan cuti karyawan secara efisien. Menggunakan arsitektur modern berbasis Laravel, Tailwind CSS, Alpine.js, Chart.js, dan DomPDF.

---

## ✨ Fitur Utama (Key Features)

### 📊 1. Multi-role Premium Dashboards
Sistem mendeteksi peran pengguna secara otomatis saat login dan mengarahkannya ke dashboard masing-masing yang interaktif dan kaya visualisasi data:
* **Dashboard Karyawan**: Menampilkan metrik Jam Lembur (Disetujui/Menunggu) serta Saldo Cuti aktif (Kuota 12 hari per tahun). Dilengkapi grafik intensitas lembur 6 bulan terakhir (*Line Chart*) dan statistik cuti melingkar (*Doughnut Gauge Chart*).
* **Dashboard Pimpinan (Pak Erwin)**: Menampilkan total operasional lembur dan cuti seluruh anggota departemennya beserta grafik Top 10 Karyawan dengan jam lembur dan cuti terlama.
* **Dashboard System Administrator**: Menampilkan ringkasan data komprehensif tingkat perusahaan, daftar Top 5 operasional, serta grafik jumlah lembur & cuti per bagian di seluruh perusahaan.

### 📅 2. Sistem Manajemen Cuti Terintegrasi (Cuti 2026)
* Pembatasan kuota cuti tahunan otomatis sebanyak **12 Hari**.
* Integrasi **Kalender Cuti Bersama & Libur Perusahaan 2026** (16 hari libur nasional + cuti bersama libur perusahaan).
* Pengurangan kuota yang adil karena sistem otomatis melompati akhir pekan dan libur nasional di atas saat pengajuan.
* Formulir Cuti cetak PDF premium yang berorientasi **A4 Portrait** dengan penempatan konten sejajar, tanda tangan digital (karyawan & pimpinan), dan pencetakan otomatis tanggal masuk kerja kembali secara dinamis.

### ⏰ 3. Manajemen Lembur (Overtime)
* Pengisian formulir lembur terstruktur dengan validasi waktu masuk dan keluar.
* Rekapitulasi lemburan bulanan karyawan yang dapat diunduh secara bulk (massal) dalam format PDF profesional.
* Halaman Perhitungan Lembur dengan tabel ringkas yang memiliki footer kalkulasi total jam lembur yang sejajar presisi di sisi kanan.

### 🧭 4. Navigasi Sidebar 4-Menu Responsif
Seluruh peran pengguna diselaraskan untuk memiliki navigasi modern yang kompak, hanya menampilkan 4 menu induk yang bisa di-collapse/expand dengan animasi panah rotasi halus menggunakan **Alpine.js**:
1. **Dashboard**
2. **Lembur** (Input Lembur, Riwayat Lembur, Perhitungan Lembur)
3. **Cuti** (Input Cuti, Riwayat Cuti, Dashboard Cuti)
4. **Profil Karyawan** (Manajemen Tanda Tangan & Data Pengguna)

---

## 🛠️ Tech Stack
* **Framework**: Laravel 11.x (PHP 8.2+)
* **Styling**: Tailwind CSS & Vanilla Custom CSS
* **Frontend Interactivity**: Alpine.js (State management & animations)
* **Visualization**: Chart.js (Line, Bar, and Doughnut charts)
* **PDF Engine**: Barryvdh DomPDF (High-fidelity corporate prints)
* **Database**: PostgreSQL / PgSQL

---

## ⚙️ Cara Menjalankan Aplikasi (Setup & Run)

Proyek ini terbagi menjadi 2 branch utama di GitHub:

### A. Branch `main` (Dengan Docker Container) 🐳
Gunakan branch ini jika Anda ingin menjalankan aplikasi di dalam container terisolasi yang sudah dikonfigurasi lengkap (Nginx, PHP, Node.js/Vite, PostgreSQL, Adminer).
1. Pastikan Docker Desktop sudah aktif di komputer Anda.
2. Jalankan perintah di bawah untuk mengaktifkan seluruh container:
   ```bash
   docker-compose up -d --build
   ```
3. Buka browser dan akses portal pada:
   * **Web Application**: `http://localhost:8001`
   * **Adminer (Database Monitoring)**: `http://localhost:8083`

---

### B. Branch `Tanpa-Docker` (Sistem Lokal Murni) 💻
Gunakan branch ini jika Anda ingin menjalankan server menggunakan PHP lokal di Mac/Windows Anda.
1. Pastikan database PostgreSQL lokal Anda sudah aktif dan sesuaikan konfigurasi koneksinya di berkas `.env`.
2. Aktifkan server PHP Laravel:
   ```bash
   php artisan serve
   ```
   * Aplikasi dapat diakses pada: `http://127.0.0.1:8000`
3. Buka tab terminal baru untuk menjalankan kompilasi asset visual menggunakan Vite:
   ```bash
   npm run dev
   ```

---

## 🔒 Akun Uji Coba (Testing Accounts)
* **System Administrator**: username `adm` | password `password`
* **Pimpinan (Erwin Setiawan)**: username `ese` | password `password`
* **Karyawan (Farhan Dwi Haryanto)**: username `fwy` | password `password`

---

## ✍️ Credits & Creator
Dibuat dengan sepenuh hati oleh **Farhan Dwi Haryanto** ❤️  
*(Disempurnakan bersama Sepuh AI 🤖)*
