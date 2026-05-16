<?php
require_once __DIR__ . '/../config.php';

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? '';
$message = '';
$error = '';

// Handle DELETE
if ($action === 'delete' && $id) {
    $id = (int)$id;
    $result = mysqli_query($db, "DELETE FROM pesanan WHERE id = $id");
    if ($result) {
        $message = "Pesanan berhasil dihapus!";
    } else {
        $error = "Gagal menghapus pesanan: " . mysqli_error($db);
    }
}

// Handle SAVE (CREATE/UPDATE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pelanggan_id = trim($_POST['pelanggan_id'] ?? '');
    $layanan_id = trim($_POST['layanan_id'] ?? '');
    $jumlah = trim($_POST['jumlah'] ?? '');
    $jenis_item = trim($_POST['jenis_item'] ?? '');
    $jenis_layanan = trim($_POST['jenis_layanan'] ?? 'reguler'); // reguler atau express
    $total_harga = trim($_POST['total_harga'] ?? '');
    $id_edit = $_POST['id_edit'] ?? '';

    // Validasi
    if (empty($pelanggan_id) || empty($layanan_id) || empty($jumlah) || empty($total_harga)) {
        $error = "Pelanggan, layanan, jumlah, dan total harga harus diisi!";
    } else {
        $pelanggan_id = (int)$pelanggan_id;
        $layanan_id = (int)$layanan_id;
        $jumlah = (float)$jumlah;
        $jenis_item = mysqli_real_escape_string($db, $jenis_item);
        $jenis_layanan = mysqli_real_escape_string($db, $jenis_layanan);
        $total_harga = (float)$total_harga;

        if ($id_edit) {
            // UPDATE
            $id_edit = (int)$id_edit;
            $query = "UPDATE pesanan SET pelanggan_id=$pelanggan_id, layanan_id=$layanan_id, jumlah=$jumlah, jenis_item='$jenis_item', jenis_layanan='$jenis_layanan', total_harga=$total_harga WHERE id=$id_edit";
            $message_text = "Pesanan berhasil diperbarui!";
        } else {
            // INSERT
            $query = "INSERT INTO pesanan (pelanggan_id, layanan_id, jumlah, jenis_item, jenis_layanan, total_harga, tanggal_masuk) VALUES ($pelanggan_id, $layanan_id, $jumlah, '$jenis_item', '$jenis_layanan', $total_harga, NOW())";
            $message_text = "Pesanan berhasil ditambahkan!";
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
    $result = mysqli_query($db, "SELECT * FROM pesanan WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result) ?? [];
}

// Get all pesanan with layanan info
$result = mysqli_query($db, "SELECT p.*, pl.nama as nama_pelanggan, l.jenis_layanan, l.kategori FROM pesanan p 
    LEFT JOIN pelanggan pl ON p.pelanggan_id = pl.id 
    LEFT JOIN layanan l ON p.layanan_id = l.id 
    ORDER BY p.id DESC");
$pesanan_list = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pesanan_list[] = $row;
    }
}

// Get all pelanggan untuk dropdown
$result_pelanggan = mysqli_query($db, "SELECT id, nama FROM pelanggan ORDER BY nama");
$pelanggan_list = [];
if ($result_pelanggan) {
    while ($row = mysqli_fetch_assoc($result_pelanggan)) {
        $pelanggan_list[] = $row;
    }
}

// Get all layanan untuk dropdown
$result_layanan = mysqli_query($db, "SELECT id, jenis_layanan, kategori, harga_reguler, harga_express FROM layanan ORDER BY jenis_layanan");
$layanan_dropdown = [];
if ($result_layanan) {
    while ($row = mysqli_fetch_assoc($result_layanan)) {
        $layanan_dropdown[] = $row;
    }
}

require_once __DIR__ . '/../header.php';
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
        transition: border-color 0.3s;
        font-family: Arial, sans-serif;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .form-group input[readonly] {
        background-color: #f8f9fa;
        cursor: not-allowed;
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

    .price-info {
        font-size: 12px;
        color: #666;
        margin-top: 3px;
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
            <h2><?php echo $action === 'edit' ? 'Edit Pesanan' : 'Tambah Pesanan Baru'; ?></h2>
            <form method="post" id="formPesanan">
                <?php if ($action === 'edit' && $edit_data): ?>
                    <input type="hidden" name="id_edit" value="<?php echo $edit_data['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label for="pelanggan_id">Nama Pelanggan</label>
                    <select id="pelanggan_id" name="pelanggan_id" required>
                        <option value="">-- Pilih Pelanggan --</option>
                        <?php foreach ($pelanggan_list as $pelanggan): ?>
                            <option value="<?php echo $pelanggan['id']; ?>" <?php echo isSelected($pelanggan['id'], $edit_data['pelanggan_id'] ?? old('pelanggan_id')); ?>>
                                <?php echo htmlspecialchars($pelanggan['nama']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="layanan_id">Pilih Layanan</label>
                    <select id="layanan_id" name="layanan_id" required onchange="updateHarga()">
                        <option value="">-- Pilih Layanan --</option>
                        <?php foreach ($layanan_dropdown as $layanan): ?>
                            <option value="<?php echo $layanan['id']; ?>" 
                                data-harga-reguler="<?php echo $layanan['harga_reguler']; ?>"
                                data-harga-express="<?php echo $layanan['harga_express']; ?>"
                                data-kategori="<?php echo $layanan['kategori']; ?>"
                                <?php echo isSelected($layanan['id'], $edit_data['layanan_id'] ?? old('layanan_id')); ?>>
                                <?php echo htmlspecialchars($layanan['jenis_layanan']); ?> (<?php echo ucfirst($layanan['kategori']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="price-info" id="hargaInfo"></div>
                </div>

                <div class="form-group" id="jenis_layanan_group" style="display: none;">
                    <label for="jenis_layanan">Jenis Layanan</label>
                    <select id="jenis_layanan" name="jenis_layanan" required onchange="hitungTotal()">
                        <option value="reguler" <?php echo isSelected('reguler', $edit_data['jenis_layanan'] ?? 'reguler'); ?>>Reguler</option>
                        <option value="express" <?php echo isSelected('express', $edit_data['jenis_layanan'] ?? 'reguler'); ?>>Express</option>
                    </select>
                    <div class="price-info" id="jenisLayananInfo"></div>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah (<span id="unitLabel">kg/pcs</span>)</label>
                    <input type="number" id="jumlah" name="jumlah" step="0.5" value="<?php echo htmlspecialchars($edit_data['jumlah'] ?? old('jumlah')); ?>" placeholder="Contoh: 2.5" required onchange="hitungTotal()" oninput="hitungTotal()">
                    <div class="price-info" id="unitInfo"></div>
                </div>

                <div class="form-group" id="jenis_item_group" style="display: none;">
                    <label for="jenis_item">Jenis Item</label>
                    <input type="text" id="jenis_item" name="jenis_item" value="<?php echo htmlspecialchars($edit_data['jenis_item'] ?? old('jenis_item')); ?>" placeholder="Contoh: Baju, Celana, Jas, Gamis, Badcover, dll">
                    <div class="price-info">Masukkan jenis pakaian yang akan disetrika</div>
                </div>

                <div class="form-group">
                    <label for="total_harga">Total Harga</label>
                    <input type="number" id="total_harga" name="total_harga" value="<?php echo htmlspecialchars($edit_data['total_harga'] ?? old('total_harga')); ?>" readonly>
                    <div class="price-info" id="totalFormatted"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $action === 'edit' ? 'Update Pesanan' : 'Simpan Pesanan'; ?>
                    </button>
                    <?php if ($action === 'edit'): ?>
                        <a href="pesanan.php" class="btn btn-secondary">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- DAFTAR PESANAN -->
        <div class="table-section">
            <h2>Daftar Pesanan</h2>
            <?php if (count($pesanan_list) === 0): ?>
                <div class="no-data">📋 Belum ada data pesanan. Silakan tambahkan pesanan baru.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Jumlah</th>
                                <th>Item / Paket</th>
                                <th>Total Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($pesanan_list as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_pelanggan'] ?? 'N/A'); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($item['jenis_layanan'] ?? 'N/A'); ?>
                                        <?php if ($item['kategori']): ?>
                                            <br><span class="badge badge-<?php echo $item['kategori']; ?>"><?php echo ucfirst($item['kategori']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $item['jumlah']; ?> <?php echo $item['kategori'] === 'kiloan' ? 'kg' : 'pcs'; ?></td>
                                    <td>
                                        <?php if ($item['kategori'] === 'kiloan'): ?>
                                            <span class="badge" style="background-color: <?php echo $item['jenis_layanan'] === 'express' ? '#ffc107' : '#17a2b8'; ?>; color: white;"><?php echo ucfirst($item['jenis_layanan']); ?></span>
                                        <?php else: ?>
                                            <?php echo htmlspecialchars($item['jenis_item'] ?? '-'); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong>Rp <?php echo number_format($item['total_harga'], 0, ',', '.'); ?></strong></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="pesanan.php?action=edit&id=<?php echo $item['id']; ?>" class="btn btn-success">Edit</a>
                                            <a href="pesanan.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
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

<script>
    // Data layanan dari database
    const layananData = <?php echo json_encode($layanan_dropdown); ?>;

    function updateHarga() {
        const layananId = document.getElementById('layanan_id').value;
        const jumlahInput = document.getElementById('jumlah');
        const hargaInfo = document.getElementById('hargaInfo');
        const unitInfo = document.getElementById('unitInfo');
        const unitLabel = document.getElementById('unitLabel');
        const jenis_item_group = document.getElementById('jenis_item_group');
        const jenis_item_input = document.getElementById('jenis_item');
        const jenis_layanan_group = document.getElementById('jenis_layanan_group');
        const jenis_layanan_select = document.getElementById('jenis_layanan');

        if (layananId) {
            const selectedOption = document.querySelector(`#layanan_id option[value="${layananId}"]`);
            const hargaReguler = parseFloat(selectedOption.dataset.hargaReguler);
            const hargaExpress = parseFloat(selectedOption.dataset.hargaExpress);
            const kategori = selectedOption.dataset.kategori;

            // Update unit label
            const kategoriText = kategori === 'kiloan' ? 'kg' : 'pcs';
            unitLabel.textContent = kategoriText;
            jumlahInput.placeholder = `Contoh: ${kategori === 'kiloan' ? '2.5' : '5'}`;
            
            // Show/hide jenis_layanan group (hanya untuk kiloan)
            if (kategori === 'kiloan') {
                jenis_layanan_group.style.display = 'block';
                jenis_layanan_select.required = true;
                unitInfo.innerHTML = `<strong>Harga Reguler: Rp ${new Intl.NumberFormat('id-ID').format(hargaReguler)} / ${kategoriText}</strong><br><strong>Harga Express: Rp ${new Intl.NumberFormat('id-ID').format(hargaExpress)} / ${kategoriText}</strong>`;
                hargaInfo.innerHTML = `<strong>Layanan Kiloan</strong>`;
            } else {
                jenis_layanan_group.style.display = 'none';
                jenis_layanan_select.required = false;
                jenis_layanan_select.value = 'reguler';
                unitInfo.innerHTML = `<strong>Harga: Rp ${new Intl.NumberFormat('id-ID').format(hargaReguler)} / ${kategoriText}</strong>`;
                hargaInfo.innerHTML = `<strong>Layanan Satuan</strong>`;
            }
            
            // Show/hide jenis_item group
            if (kategori === 'satuan') {
                jenis_item_group.style.display = 'block';
                jenis_item_input.required = true;
            } else {
                jenis_item_group.style.display = 'none';
                jenis_item_input.required = false;
                jenis_item_input.value = '';
            }
            
            hitungTotal();
        }
    }

    function hitungTotal() {
        const layananId = document.getElementById('layanan_id').value;
        const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
        const jenis_layanan = document.getElementById('jenis_layanan').value || 'reguler';
        const totalInput = document.getElementById('total_harga');
        const totalFormatted = document.getElementById('totalFormatted');
        const jenisLayananInfo = document.getElementById('jenisLayananInfo');

        if (layananId && jumlah > 0) {
            const selectedOption = document.querySelector(`#layanan_id option[value="${layananId}"]`);
            const hargaReguler = parseFloat(selectedOption.dataset.hargaReguler);
            const hargaExpress = parseFloat(selectedOption.dataset.hargaExpress);
            
            // Pilih harga berdasarkan jenis layanan
            let hargaFinal = jenis_layanan === 'express' ? hargaExpress : hargaReguler;
            const total = hargaFinal * jumlah;
            
            totalInput.value = total;
            totalFormatted.innerHTML = `<strong>Rp ${new Intl.NumberFormat('id-ID').format(total)}</strong>`;
            jenisLayananInfo.innerHTML = `<strong style="color: #007bff;">Tipe: ${jenis_layanan.toUpperCase()}</strong>`;
        } else {
            totalInput.value = 0;
            totalFormatted.innerHTML = '';
            jenisLayananInfo.innerHTML = '';
        }
    }

    // Initialize on page load
    window.addEventListener('load', function() {
        updateHarga();
    });
</script>

<?php require_once __DIR__ . '/../footer.php'; ?>
