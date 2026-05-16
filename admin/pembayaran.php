<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['pembayaran'][] = [
        'metode' => $_POST['metode'] ?? '',
        'jumlah_dibayar' => $_POST['jumlah_dibayar'] ?? 0,
        'bukti' => $_POST['bukti'] ?? '',
    ];
    header('Location: pembayaran.php');
    exit;
}
require_once __DIR__ . '/../layouts/admin_header.php';
?>
<section>
    <h2>Data Pembayaran</h2>
    <form method="post">
        <label>Metode Pembayaran</label>
        <select name="metode" required>
            <option value="">-- Pilih Metode --</option>
            <option value="Tunai" <?php echo isSelected('Tunai', old('metode')); ?>>Tunai</option>
            <option value="Transfer" <?php echo isSelected('Transfer', old('metode')); ?>>Transfer</option>
        </select>

        <label>Jumlah Dibayar</label>
        <input type="number" name="jumlah_dibayar" step="0.01" value="<?php echo htmlspecialchars(old('jumlah_dibayar')); ?>" required>

        <label>Bukti Pembayaran (jika Transfer)</label>
        <input type="text" name="bukti" value="<?php echo htmlspecialchars(old('bukti')); ?>" placeholder="Nama file atau detail transfer">

        <button type="submit">Simpan Pembayaran</button>
    </form>
</section>
<section>
    <h2>Daftar Pembayaran</h2>
    <?php if (count($_SESSION['pembayaran']) === 0): ?>
        <p>Belum ada data pembayaran.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Metode</th>
                <th>Jumlah Dibayar</th>
                <th>Bukti</th>
            </tr>
            <?php foreach ($_SESSION['pembayaran'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['metode']); ?></td>
                    <td>Rp <?php echo number_format($item['jumlah_dibayar'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($item['bukti']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>
