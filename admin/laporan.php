<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$transaksi = getAll('transaksi');
$pengeluaran = getAll('pengeluaran');

$total_pemasukan = array_sum(array_column($transaksi, 'total_biaya'));
$total_pengeluaran = array_sum(array_column($pengeluaran, 'jumlah'));
$laba = $total_pemasukan - $total_pengeluaran;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Kyka Laundry</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-layout">
<header>
    <div class="topbar">
        <h1>Kyka Laundry - Laporan</h1>
        <p>Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?> | <a href="../logout.php" style="color: #fff;">Logout</a></p>
    </div>
    <nav>
        <a href="../dashboard.php">Dashboard</a>
        <a href="users.php">Data Admin</a>
        <a href="pelanggan.php">Data Pelanggan</a>
        <a href="layanan.php">Data Layanan</a>
        <a href="pesanan.php">Data Pesanan</a>
        <a href="pengeluaran.php">Data Pengeluaran</a>
        <a href="laporan.php">Laporan</a>
    </nav>
    <main>
<section>
    <h2>Laporan Keuangan</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 20px;">
        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); text-align: center;">
            <h3>Total Pemasukan</h3>
            <p>Rp <?php echo number_format($total_pemasukan, 2, ',', '.'); ?></p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); text-align: center;">
            <h3>Total Pengeluaran</h3>
            <p>Rp <?php echo number_format($total_pengeluaran, 2, ',', '.'); ?></p>
        </div>
        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); text-align: center;">
            <h3>Laba/Rugi</h3>
            <p>Rp <?php echo number_format($laba, 2, ',', '.'); ?></p>
        </div>
    </div>

    <h3>Detail Pemasukan (Transaksi)</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Tgl Masuk</th>
            <th>Tgl Selesai</th>
            <th>Total Biaya</th>
        </tr>
        <?php foreach ($transaksi as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['id']); ?></td>
                <td><?php echo htmlspecialchars($item['tanggal_masuk']); ?></td>
                <td><?php echo htmlspecialchars($item['tanggal_selesai']); ?></td>
                <td>Rp <?php echo number_format($item['total_biaya'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Detail Pengeluaran</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
        </tr>
        <?php foreach ($pengeluaran as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['id']); ?></td>
                <td><?php echo htmlspecialchars($item['tanggal']); ?></td>
                <td><?php echo htmlspecialchars($item['keterangan']); ?></td>
                <td>Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
    </main>
    <footer>
        <p>&copy; 2026 Kyka Laundry. Sistem Informasi Laundry.</p>
    </footer>
</body>
</html>