<?php
require_once __DIR__ . '/../config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['status'][] = [
        'nama_pelanggan' => $_POST['nama_pelanggan'] ?? '',
        'status' => $_POST['status'] ?? '',
    ];
    header('Location: status.php');
    exit;
}
require_once __DIR__ . '/../header.php';
?>
<section>
    <h2>Status Pengerjaan</h2>
    <form method="post">
        <label>Nama Pelanggan</label>
        <input type="text" name="nama_pelanggan" value="<?php echo htmlspecialchars(old('nama_pelanggan')); ?>" required>

        <label>Status</label>
        <select name="status" required>
            <option value="">-- Pilih Status --</option>
            <option value="diterima" <?php echo isSelected('diterima', old('status')); ?>>Diterima</option>
            <option value="dicuci" <?php echo isSelected('dicuci', old('status')); ?>>Dicuci</option>
            <option value="disetrika" <?php echo isSelected('disetrika', old('status')); ?>>Disetrika</option>
            <option value="siap diambil" <?php echo isSelected('siap diambil', old('status')); ?>>Siap Diambil</option>
        </select>

        <button type="submit">Simpan Status</button>
    </form>
</section>
<section>
    <h2>Daftar Status</h2>
    <?php if (count($_SESSION['status']) === 0): ?>
        <p>Belum ada data status.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Nama Pelanggan</th>
                <th>Status</th>
            </tr>
            <?php foreach ($_SESSION['status'] as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['nama_pelanggan']); ?></td>
                    <td><?php echo htmlspecialchars($item['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/../footer.php'; ?>
