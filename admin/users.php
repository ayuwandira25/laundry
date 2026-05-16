<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$users = getAll('users');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Admin - Kyka Laundry</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-layout">
<header>
    <div class="topbar">
        <h1>Kyka Laundry - Data Admin</h1>
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
    <h2>Data Admin</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Nama</th>
            <th>Role</th>
            <th>Dibuat</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['nama']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
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