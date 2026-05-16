<?php
require_once __DIR__ . '/../config.php';
$page_title = 'Pengeluaran';
$current_page = 'pengeluaran';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['pengeluaran'][] = [
        'tanggal' => $_POST['tanggal'] ?? '',
        'keterangan' => $_POST['keterangan'] ?? '',
        'jumlah' => $_POST['jumlah'] ?? 0,
    ];
    header('Location: pengeluaran.php');
    exit;
}
require_once __DIR__ . '/../layouts/admin_header.php';
?>
<section>
    <h2>Data Pengeluaran</h2>
    <form method="post">
        <label>Tanggal Pengeluaran</label>
        <input type="date" name="tanggal" value="<?php echo htmlspecialchars(old('tanggal')); ?>" required>

        <label>Keterangan</label>
        <input type="text" name="keterangan" value="<?php echo htmlspecialchars(old('keterangan')); ?>" placeholder="Deterjen, pewangi, bahan baku" required>

        <label>Jumlah Pengeluaran</label>
        <input type="number" name="jumlah" step="0.01" value="<?php echo htmlspecialchars(old('jumlah')); ?>" required>

        <button type="submit">Simpan Pengeluaran</button>
    </form>
</section>
<section>
    <h2>Daftar Pengeluaran</h2>
    <?php if (count($_SESSION['pengeluaran']) === 0): ?>
        <p>Belum ada data pengeluaran.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
            </tr>
            <?php foreach ($_SESSION['pengeluaran'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['tanggal']); ?></td>
                    <td><?php echo htmlspecialchars($item['keterangan']); ?></td>
                    <td>Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
