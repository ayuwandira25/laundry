<?php
$page_title = 'Layanan';
$current_page = 'layanan';
require_once __DIR__ . '/../config.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$message = '';
$error = '';

// Handle DELETE
if ($action === 'delete' && $id) {
    $id = (int)$id;
    $result = mysqli_query($db, "DELETE FROM layanan WHERE id = $id");
    if ($result) {
        $message = "Layanan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus layanan: " . mysqli_error($db);
    }
}

// Handle SAVE (CREATE/UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori = trim($_POST['kategori'] ?? '');
    $jenis_layanan = trim($_POST['jenis_layanan'] ?? '');
    $harga_reguler = trim($_POST['harga_reguler'] ?? '');
    $harga_express = trim($_POST['harga_express'] ?? '');
    $id_edit = $_POST['id_edit'] ?? '';

    if (empty($kategori) || empty($jenis_layanan) || empty($harga_reguler) || empty($harga_express)) {
        $error = "Kategori, jenis, dan harga harus diisi!";
    } else {
        $kategori = mysqli_real_escape_string($db, $kategori);
        $jenis_layanan = mysqli_real_escape_string($db, $jenis_layanan);
        $harga_reguler = (float)$harga_reguler;
        $harga_express = (float)$harga_express;

        if ($id_edit) {
            // UPDATE
            $id_edit = (int)$id_edit;
            $query = "UPDATE layanan SET kategori='$kategori', jenis_layanan='$jenis_layanan', harga_reguler=$harga_reguler, harga_express=$harga_express WHERE id=$id_edit";
            $message_text = "Layanan berhasil diperbarui!";
        } else {
            // INSERT
            $query = "INSERT INTO layanan (kategori, jenis_layanan, harga_reguler, harga_express) VALUES ('$kategori', '$jenis_layanan', $harga_reguler, $harga_express)";
            $message_text = "Layanan berhasil ditambahkan!";
        }

        if (mysqli_query($db, $query)) {
            $message = $message_text;
            $_POST = []; // Clear form
        } else {
            $error = "Gagal menyimpan: " . mysqli_error($db);
        }
    }
}

// Get data untuk edit
$edit_data = [];
if ($action === 'edit' && $id) {
    $id = (int)$id;
    $result = mysqli_query($db, "SELECT * FROM layanan WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result) ?? [];
}

// Get all layanan
$result = mysqli_query($db, "SELECT * FROM layanan ORDER BY id DESC");
$layanan_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $layanan_list[] = $row;
    }
}

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .form-section {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .form-section h2 {
        margin-top: 0;
        color: #333;
        font-size: 20px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 500;
        font-size: 14px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.3s;
        font-family: Arial, sans-serif;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #545b62;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .table-section {
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .table-section h2 {
        margin-top: 0;
        color: #333;
        font-size: 20px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    table thead {
        background-color: #f8f9fa;
        border-bottom: 2px solid #007bff;
    }

    table th {
        padding: 12px;
        text-align: left;
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    table td {
        padding: 12px;
        border-bottom: 1px solid #ddd;
        font-size: 14px;
        color: #555;
    }

    table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .no-data {
        text-align: center;
        padding: 30px;
        color: #999;
        font-size: 14px;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-kiloan {
        background-color: #cfe2ff;
        color: #084298;
    }

    .badge-satuan {
        background-color: #d1e7dd;
        color: #0f5132;
    }

    .row {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .row {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container">
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- FORM INPUT -->
        <div class="form-section">
            <h2><?php echo $action === 'edit' ? 'Edit Layanan' : 'Tambah Layanan Baru'; ?></h2>
            <form method="post">
                <?php if ($action === 'edit' && $edit_data): ?>
                    <input type="hidden" name="id_edit" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori" required onchange="updateJenisLayanan()">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="kiloan" <?php echo isSelected('kiloan', $edit_data['kategori'] ?? old('kategori')); ?>>Kiloan</option>
                            <option value="satuan" <?php echo isSelected('satuan', $edit_data['kategori'] ?? old('kategori')); ?>>Satuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_layanan">Jenis Layanan</label>

                        <!-- Dropdown untuk Kiloan -->
                        <select id="jenis_layanan_select" style="display: none;">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Cuci Setrika" <?php echo isSelected('Cuci Setrika', $edit_data['jenis_layanan'] ?? old('jenis_layanan')); ?>>Cuci Setrika</option>
                            <option value="Cuci Kering" <?php echo isSelected('Cuci Kering', $edit_data['jenis_layanan'] ?? old('jenis_layanan')); ?>>Cuci Kering</option>
                            <option value="Setrika Saja" <?php echo isSelected('Setrika Saja', $edit_data['jenis_layanan'] ?? old('jenis_layanan')); ?>>Setrika Saja</option>
                        </select>

                        <!-- Input text untuk Satuan -->
                        <input type="text" id="jenis_layanan_input" style="display: none;" value="<?php echo htmlspecialchars($edit_data['jenis_layanan'] ?? old('jenis_layanan')); ?>" placeholder="Masukkan jenis layanan (contoh: Baju Batik, Celana Panjang, dll)">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="harga_reguler">Harga Reguler (untuk Kiloan)</label>
                        <input type="number" id="harga_reguler" name="harga_reguler" step="100" value="<?php echo htmlspecialchars($edit_data['harga_reguler'] ?? old('harga_reguler')); ?>" placeholder="Contoh: 5000" required>
                    </div>

                    <div class="form-group">
                        <label for="harga_express">Harga Express (untuk Satuan)</label>
                        <input type="number" id="harga_express" name="harga_express" step="100" value="<?php echo htmlspecialchars($edit_data['harga_express'] ?? old('harga_express')); ?>" placeholder="Contoh: 7500" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'edit' ? 'Update Layanan' : 'Simpan Layanan'; ?>
                    </button>
                    <?php if ($action === 'edit'): ?>
                        <a href="layanan.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>

            <script>
                function updateJenisLayanan() {
                    const kategori = document.getElementById('kategori').value;
                    const selectField = document.getElementById('jenis_layanan_select');
                    const inputField = document.getElementById('jenis_layanan_input');

                    if (kategori === 'kiloan') {
                        selectField.style.display = 'block';
                        selectField.name = 'jenis_layanan';
                        inputField.style.display = 'none';
                        inputField.name = '';
                    } else if (kategori === 'satuan') {
                        selectField.style.display = 'none';
                        selectField.name = '';
                        inputField.style.display = 'block';
                        inputField.name = 'jenis_layanan';
                    } else {
                        selectField.style.display = 'none';
                        selectField.name = '';
                        inputField.style.display = 'none';
                        inputField.name = '';
                    }
                }

                // Validasi form sebelum submit
                document.addEventListener('DOMContentLoaded', function() {
                    updateJenisLayanan();

                    const form = document.querySelector('form');
                    form.addEventListener('submit', function(e) {
                        const kategori = document.getElementById('kategori').value;
                        const selectField = document.getElementById('jenis_layanan_select');
                        const inputField = document.getElementById('jenis_layanan_input');

                        let jenisLayanan = '';
                        if (kategori === 'kiloan') {
                            jenisLayanan = selectField.value;
                        } else if (kategori === 'satuan') {
                            jenisLayanan = inputField.value;
                        }

                        if (!kategori || !jenisLayanan) {
                            e.preventDefault();
                            alert('Kategori dan Jenis Layanan harus diisi!');
                        }
                    });
                });
            </script>
        </div>

        <!-- DAFTAR LAYANAN -->
        <div class="table-section">
            <h2>Daftar Layanan</h2>
            <?php if (count($layanan_list) === 0): ?>
                <div class="no-data">📋 Belum ada data layanan. Silakan tambahkan layanan baru.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th>Harga (Reguler / Express)</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($layanan_list as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><span class="badge badge-<?php echo $item['kategori']; ?>"><?php echo ucfirst($item['kategori']); ?></span></td>
                                    <td><?php echo htmlspecialchars($item['jenis_layanan']); ?></td>
                                    <td>
                                        Rp <?php echo number_format($item['harga_reguler'], 0, ',', '.'); ?> /
                                        Rp <?php echo number_format($item['harga_express'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="layanan.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-success">Edit</a>
                                            <a href="layanan.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>