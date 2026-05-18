<?php
require_once __DIR__ . '/../config.php';
$page_title = 'Status';
$current_page = 'status';

// Handle DELETE
if ((isset($_GET['action']) && $_GET['action'] === 'delete') && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($db, "DELETE FROM pesanan WHERE id = $id");
}

// Handle SAVE (CREATE/UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pesanan_id = (int)($_POST['pesanan_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    if ($pesanan_id && $status) {
        $status = mysqli_real_escape_string($db, $status);
        $query = "UPDATE pesanan SET status='$status' WHERE id=$pesanan_id";
        if (mysqli_query($db, $query)) {
            $message = "Status berhasil diperbarui!";
        } else {
            $error = "Gagal menyimpan: " . mysqli_error($db);
        }
    }
}

// Get all pesanan dengan pelanggan info
$result = mysqli_query($db, "SELECT p.id, p.status, pl.nama as nama_pelanggan, l.jenis_layanan, l.kategori FROM pesanan p 
    LEFT JOIN pelanggan pl ON p.pelanggan_id = pl.id 
    LEFT JOIN layanan l ON p.layanan_id = l.id 
    ORDER BY p.id DESC");
$pesanan_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pesanan_list[] = $row;
    }
}

// Get pelanggan list for dropdown (dari pesanan yang ada)
$result_pelanggan = mysqli_query($db, "SELECT DISTINCT p.id, pl.nama FROM pesanan p 
    LEFT JOIN pelanggan pl ON p.pelanggan_id = pl.id 
    WHERE pl.nama IS NOT NULL 
    ORDER BY pl.nama");
$pelanggan_list = [];
if ($result_pelanggan) {
    while ($row = mysqli_fetch_assoc($result_pelanggan)) {
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
    .form-group select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .form-group input:focus,
    .form-group select:focus {
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

    .btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 6px 12px;
        font-size: 12px;
    }

    .btn-danger:hover {
        background-color: #c82333;
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

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-diproses {
        background-color: #fff3cd;
        color: #856404;
    }

    .badge-selesai {
        background-color: #d4edda;
        color: #155724;
    }

    .action-buttons {
        display: flex;
        gap: 8px;
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
    }
</style>

<div class="container">
    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- FORM INPUT -->
        <div class="form-section">
            <h2>Update Status Pengerjaan</h2>
            <form method="post" id="formStatus">
                <div class="form-group">
                    <label for="pesanan_id">Pilih Pelanggan / Pesanan</label>
                    <select id="pesanan_id" name="pesanan_id" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pesanan_list as $pesanan): ?>
                            <option value="<?php echo $pesanan['id']; ?>">
                                <?php echo htmlspecialchars($pesanan['nama_pelanggan'] ?? 'N/A'); ?> - <?php echo htmlspecialchars($pesanan['jenis_layanan'] ?? 'N/A'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Status Pengerjaan</label>
                    <select id="status" name="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="diproses">Diproses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Status</button>
                </div>
            </form>
        </div>

        <!-- DAFTAR STATUS -->
        <div class="table-section">
            <h2>Daftar Status Pengerjaan</h2>
            <?php if (count($pesanan_list) === 0): ?>
                <div class="no-data">📋 Belum ada data pesanan. Silakan tambahkan pesanan terlebih dahulu.</div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($pesanan_list as $item): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($item['nama_pelanggan'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($item['jenis_layanan'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower($item['status'] ?? 'diproses'); ?>">
                                        <?php echo ucfirst($item['status'] ?? 'Diproses'); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="status.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

