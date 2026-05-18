-- Membuat tabel untuk aplikasi laundry

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    nama VARCHAR(255) NOT NULL,
    jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'pelanggan') DEFAULT 'pelanggan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS register (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    jenis_kelamin VARCHAR(20) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'pelanggan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    email VARCHAR(255) NOT NULL,
    nama VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pelanggan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    telepon VARCHAR(20),
    alamat TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS layanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori ENUM('kiloan', 'satuan') NOT NULL,
    jenis_layanan VARCHAR(255) NOT NULL COMMENT 'Cuci Kering, Cuci Setrika, Setrika Saja, dll',
    harga_reguler DECIMAL(10,2) NOT NULL,
    harga_express DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    jabatan VARCHAR(255),
    hp VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS gaji (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE,
    nama_karyawan VARCHAR(255),
    jumlah_harian DECIMAL(10,2)
);

CREATE TABLE IF NOT EXISTS pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelanggan_id INT,
    layanan_id INT,
    jumlah DECIMAL(10,2),
    jenis_item VARCHAR(255),
    jenis_layanan VARCHAR(50),
    total_harga DECIMAL(10,2),
    tanggal_masuk TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tanggal_selesai DATE,
    status VARCHAR(50) DEFAULT 'pending',
    FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE SET NULL,
    FOREIGN KEY (layanan_id) REFERENCES layanan(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_masuk DATE,
    tanggal_selesai DATE,
    total_biaya DECIMAL(10,2)
);

CREATE TABLE IF NOT EXISTS pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    metode VARCHAR(50),
    jumlah_dibayar DECIMAL(10,2),
    bukti VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS pengeluaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE,
    keterangan VARCHAR(255),
    jumlah DECIMAL(10,2)
);

CREATE TABLE IF NOT EXISTS status_pengerjaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(255),
    status VARCHAR(100)
);