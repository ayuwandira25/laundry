<?php
$page_title = 'Pelanggan';
$current_page = 'pelanggan';
require_once __DIR__ . '/../config.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$message = '';
$error = '';

// Handle DELETE
if ($action === 'delete' && $id) {
    $id = (int)$id;
    $result = mysqli_query($db, "DELETE FROM pelanggan WHERE id = $id");
    if ($result) {
        $message = "Pelanggan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pelanggan: " . mysqli_error($db);
    }
}

// Handle SAVE (CREATE/UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');
    $id_edit = $_POST['id_edit'] ?? '';

    if (empty($nama) || empty($telepon) || empty($alamat)) {
        $error = "Semua field harus diisi!";
    } else {
        $nama = mysqli_real_escape_string($db, $nama);
        $telepon = mysqli_real_escape_string($db, $telepon);
        $alamat = mysqli_real_escape_string($db, $alamat);

        if ($id_edit) {
            // UPDATE
            $id_edit = (int)$id_edit;
            $query = "UPDATE pelanggan SET nama='$nama', telepon='$telepon', alamat='$alamat' WHERE id=$id_edit";
            $message_text = "Pelanggan berhasil diperbarui!";
        } else {
            // INSERT
            $query = "INSERT INTO pelanggan (nama, telepon, alamat) VALUES ('$nama', '$telepon', '$alamat')";
            $message_text = "Pelanggan berhasil ditambahkan!";
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
    $result = mysqli_query($db, "SELECT * FROM pelanggan WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result) ?? [];
}

// Get all pelanggan
$result = mysqli_query($db, "SELECT * FROM pelanggan ORDER BY id DESC");
$pelanggan_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pelanggan_list[] = $row;
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
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
    .form-group textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
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
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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

    .row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .row {
            grid-template-columns: 1fr;
        }
    }
</style>

                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="row">
                    <!-- FORM INPUT -->
                    <div class="form-section">
                        <h2><?php echo $action === 'edit' ? 'Edit Pelanggan' : 'Tambah Pelanggan Baru'; ?></h2>
                        <form method="post">
                            <?php if ($action === 'edit' && $edit_data): ?>
                                <input type="hidden" name="id_edit" value="<?php echo $edit_data['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="nama">Nama Pelanggan</label>
                                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($edit_data['nama'] ?? old('nama')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="telepon">Nomor Telepon</label>
                                <input type="text" id="telepon" name="telepon" value="<?php echo htmlspecialchars($edit_data['telepon'] ?? old('telepon')); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea id="alamat" name="alamat" rows="4" placeholder="Masukkan alamat lengkap" required><?php echo htmlspecialchars($edit_data['alamat'] ?? old('alamat')); ?></textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $action === 'edit' ? 'Update Pelanggan' : 'Simpan Pelanggan'; ?>
                                </button>
                                <?php if ($action === 'edit'): ?>
                                    <a href="pelanggan.php" class="btn btn-secondary">Batal</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- DAFTAR PELANGGAN -->
                    <div class="table-section">
                        <h2>Daftar Pelanggan</h2>
                        <?php if (count($pelanggan_list) === 0): ?>
                            <div class="no-data">📋 Belum ada data pelanggan. Silakan tambahkan pelanggan baru.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Telepon</th>
                                            <th>Alamat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach ($pelanggan_list as $item): ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                                <td><?php echo htmlspecialchars($item['telepon']); ?></td>
                                                <td><?php echo htmlspecialchars($item['alamat']); ?></td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="pelanggan.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-success">Edit</a>
                                                        <a href="pelanggan.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
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
