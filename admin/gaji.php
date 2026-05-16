<?php
require_once __DIR__ . '/../config.php';
$page_title = 'Gaji';
$current_page = 'gaji';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['gaji'][] = [
        'tanggal' => $_POST['tanggal'] ?? '',
        'nama_karyawan' => $_POST['nama_karyawan'] ?? '',
        'jumlah_harian' => $_POST['jumlah_harian'] ?? 0,
    ];
    header('Location: gaji.php');
    exit;
}
require_once __DIR__ . '/../layouts/admin_header.php';
?>
<section>
    <h2>Data Gaji</h2>
    <form method="post">
        <label>Tanggal Masuk Kerja</label>
        <input type="date" name="tanggal" value="<?php echo htmlspecialchars(old('tanggal')); ?>" required>

        <label>Nama Karyawan</label>
        <input type="text" name="nama_karyawan" value="<?php echo htmlspecialchars(old('nama_karyawan')); ?>" required>

        <label>Jumlah Gaji Harian</label>
        <input type="number" name="jumlah_harian" step="0.01" value="<?php echo htmlspecialchars(old('jumlah_harian')); ?>" required>

        <button type="submit">Simpan Gaji</button>
    </form>
</section>
<section>
    <h2>Daftar Gaji</h2>
    <?php if (count($_SESSION['gaji']) === 0): ?>
        <p>Belum ada data gaji.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Nama Karyawan</th>
                <th>Gaji Harian</th>
            </tr>
            <?php foreach ($_SESSION['gaji'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['tanggal']); ?></td>
                    <td><?php echo htmlspecialchars($item['nama_karyawan']); ?></td>
                    <td>Rp <?php echo number_format($item['jumlah_harian'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
