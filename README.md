# Kyka Laundry

Aplikasi web PHP native untuk sistem informasi laundry Kyka Laundry.

## Fitur
- Data pelanggan
- Data layanan / item
- Data karyawan
- Data gaji harian
- Data pesanan
- Data transaksi laundry
- Data pembayaran
- Data pengeluaran
- Data status pengerjaan

## Struktur folder
- `index.php`: halaman beranda
- `config.php`: konfigurasi session dan data sementara
- `header.php`, `footer.php`: template tampilan
- `assets/css/style.css`: gaya tampilan
- `admin/`: halaman pengelolaan data

## Jalankan
1. Salin folder `Kyka_Laundry` ke `htdocs` XAMPP.
2. Buka browser ke `http://localhost/KykaLaundry/Kyka_Laundry/index.php`.
3. Gunakan menu untuk mengelola data.

## Catatan
- Data disimpan sementara dalam session browser.
- Untuk penyimpanan permanen, gunakan database MySQL dan koneksi PHP.
