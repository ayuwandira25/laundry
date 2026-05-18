<?php
$page_title = 'Karyawan';
$current_page = 'karyawan';
require_once __DIR__ . '/../config.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$message = '';
$error = '';

// Handle DELETE
if ($action === 'delete' && $id) {
    $id = (int)$id;
    $result = mysqli_query($db, "DELETE FROM karyawan WHERE id = $id");
    if ($result) {
        $message = "Karyawan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus karyawan: " . mysqli_error($db);
    }
}

// Handle SAVE (CREATE/UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $jabatan = trim($_POST['jabatan'] ?? '');
    $hp = trim($_POST['hp'] ?? '');
    $id_edit = $_POST['id_edit'] ?? '';

    if (empty($nama) || empty($jabatan) || empty($hp)) {
        $error = "Semua field harus diisi!";
    } else {
        $nama = mysqli_real_escape_string($db, $nama);
        $jabatan = mysqli_real_escape_string($db, $jabatan);
        $hp = mysqli_real_escape_string($db, $hp);

        if ($id_edit) {
            // UPDATE
            $id_edit = (int)$id_edit;
            $query = "UPDATE karyawan SET nama='$nama', jabatan='$jabatan', hp='$hp' WHERE id=$id_edit";
            $message_text = "Karyawan berhasil diperbarui!";
        } else {
            // INSERT
            $query = "INSERT INTO karyawan (nama, jabatan, hp) VALUES ('$nama', '$jabatan', '$hp')";
            $message_text = "Karyawan berhasil ditambahkan!";
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
    $result = mysqli_query($db, "SELECT * FROM karyawan WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result) ?? [];
}

// Get all karyawan
$result = mysqli_query($db, "SELECT * FROM karyawan ORDER BY id DESC");
$karyawan_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawan_list[] = $row;
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

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
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
            <h2><?php echo $action === 'edit' ? 'Edit Karyawan' : 'Tambah Karyawan Baru'; ?></h2>
            <form method="post">
                <?php if ($action === 'edit' && $edit_data): ?>
                    <input type="hidden" name="id_edit" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="nama">Nama Karyawan</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($edit_data['nama'] ?? old('nama')); ?>" required>
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($edit_data['jabatan'] ?? old('jabatan')); ?>" required>
                </div>

                <div class="form-group">
                    <label for="hp">Nomor HP</label>
                    <input type="text" id="hp" name="hp" value="<?php echo htmlspecialchars($edit_data['hp'] ?? old('hp')); ?>" required>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'edit' ? 'Update Karyawan' : 'Simpan Karyawan'; ?>
                    </button>
                    <?php if ($action === 'edit'): ?>
                        <a href="karyawan.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- DAFTAR KARYAWAN -->
        <div class="table-section">
            <h2>Daftar Karyawan</h2>
            <?php if (count($karyawan_list) === 0): ?>
                <div class="no-data">📋 Belum ada data karyawan. Silakan tambahkan karyawan baru.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($karyawan_list as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($item['jabatan']); ?></td>
                                    <td><?php echo htmlspecialchars($item['hp']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="karyawan.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-success">Edit</a>
                                            <a href="karyawan.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
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