<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['transaksi'][] = [
        'tanggal_masuk' => $_POST['tanggal_masuk'] ?? '',
        'tanggal_selesai' => $_POST['tanggal_selesai'] ?? '',
        'total_biaya' => $_POST['total_biaya'] ?? 0,
    ];
    header('Location: transaksi.php');
    exit;
}
require_once __DIR__ . '/../header.php';
?>
<section>
    <h2>Data Transaksi Laundry</h2>
    <form method="post">
        <label>Tanggal Masuk</label>
        <input type="date" name="tanggal_masuk" value="<?php echo htmlspecialchars(old('tanggal_masuk')); ?>" required>

        <label>Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" value="<?php echo htmlspecialchars(old('tanggal_selesai')); ?>" required>

        <label>Total Biaya</label>
        <input type="number" name="total_biaya" step="0.01" value="<?php echo htmlspecialchars(old('total_biaya')); ?>" required>

        <button type="submit">Simpan Transaksi</button>
    </form>
</section>
<section>
    <h2>Daftar Transaksi</h2>
    <?php if (count($_SESSION['transaksi']) === 0): ?>
        <p>Belum ada data transaksi.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Tgl Masuk</th>
                <th>Tgl Selesai</th>
                <th>Total Biaya</th>
            </tr>
            <?php foreach ($_SESSION['transaksi'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['tanggal_masuk']); ?></td>
                    <td><?php echo htmlspecialchars($item['tanggal_selesai']); ?></td>
                    <td>Rp <?php echo number_format($item['total_biaya'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>
