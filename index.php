<?php
require_once __DIR__ . '/config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/header.php';
?>
<section>
    <h2>Selamat datang di Kyka Laundry</h2>
    <p>Ini adalah sistem informasi laundry sederhana yang memuat data pelanggan, layanan, karyawan, gaji, pesanan, transaksi, pembayaran, pengeluaran, dan status pengerjaan.</p>
    <ul>
        <li>Admin dapat mengelola data pelanggan dan pelanggan memasukkan data layanan berupa harga, jumlah pakaian, dan total biaya.</li>
        <li>Karyawan dapat mengelola data jabatan dan gaji harian.</li>
        <li>Data pesanan, transaksi, pembayaran, dan status pengerjaan tersedia untuk mendukung proses laundry.</li>
    </ul>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>
