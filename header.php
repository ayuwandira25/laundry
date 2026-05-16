<?php
require_once __DIR__ . '/config.php';

$is_logged_in = isset($_SESSION['user_id']) && isset($_SESSION['user_role']);
$user_role = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kyka Laundry</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <div class="topbar">
        <h1>Kyka Laundry</h1>
        <?php if ($is_logged_in): ?>
            <a href="?logout=1" class="button logout-button">Logout</a>
        <?php endif; ?>
    </div>
</header>
<?php if ($is_logged_in): ?>
<nav>
    <a href="index.php">Beranda</a>
    <?php if ($user_role === 'admin'): ?>
    <a href="admin/pelanggan.php">Pelanggan</a>
    <a href="admin/layanan.php">Data Layanan</a>
    <a href="admin/karyawan.php">Karyawan</a>
    <a href="admin/gaji.php">Gaji</a>
    <a href="admin/pesanan.php">Pesanan</a>
    <a href="admin/transaksi.php">Transaksi</a>
    <a href="admin/pembayaran.php">Pembayaran</a>
    <a href="admin/pengeluaran.php">Pengeluaran</a>
    <a href="admin/status.php">Status</a>
    <a href="admin/users.php">Users</a>
    <a href="admin/laporan.php">Laporan</a>
    <?php endif; ?>
    <a href="?logout=1" class="logout">Logout (<?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?>)</a>
</nav>
<?php endif; ?>
<main>
