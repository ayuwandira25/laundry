<?php
require_once __DIR__ . '/../config.php';
$page_title = 'Pembayaran';
$current_page = 'pembayaran';

// Inisialisasi session pembayaran jika belum ada
if (!isset($_SESSION['pembayaran'])) {
    $_SESSION['pembayaran'] = [];
}

// Ambil data pesanan dari database
$pesanan_list = [];
$result = mysqli_query($db, "SELECT p.id, pl.nama as nama_pelanggan, p.total_harga FROM pesanan p 
    LEFT JOIN pelanggan pl ON p.pelanggan_id = pl.id 
    ORDER BY p.id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $pesanan_list[] = $row;
    }
}

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

<style>
    .page-wrapper {
        padding: 20px;
    }

    .content-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 25px;
    }

    .form-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 14px 15px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        background: #f8fafc;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #1e293b;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(30, 41, 59, 0.08);
    }

    .btn-save {
        margin-top: 25px;
        padding: 14px 25px;
        border: none;
        border-radius: 12px;
        background: #1e293b;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-save:hover {
        background: #0f172a;
        transform: translateY(-2px);
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th {
        background: #1e293b;
        color: #fff;
        padding: 15px;
        text-align: left;
        font-size: 14px;
    }

    table td {
        padding: 15px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #334155;
    }

    table tr:hover {
        background: #f8fafc;
    }

    .badge-method {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #e2e8f0;
        color: #1e293b;
        font-size: 13px;
        font-weight: 600;
    }

    .amount {
        font-weight: 700;
        color: #0f172a;
    }

    .empty-data {
        padding: 20px;
        text-align: center;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
    }

    @media(max-width:768px) {

        .content-card {
            padding: 20px;
        }

        .section-title {
            font-size: 20px;
        }
    }
</style>

<div class="page-wrapper">

    <!-- FORM PEMBAYARAN -->
    <section class="content-card">

        <h2 class="section-title">
            Form Pembayaran
        </h2>

        <form method="post">

            <div class="form-layout">

                <!-- Nama Pesanan -->
                <div class="form-group">

                    <label>Nama Pesanan</label>

                    <select id="pesananSelect" name="pesanan_id" class="form-control" required>

                        <option value="">
                            -- Pilih Pesanan --
                        </option>

                        <?php foreach ($pesanan_list as $pesanan): ?>

                            <option value="<?php echo htmlspecialchars($pesanan['id']); ?>" data-harga="<?php echo htmlspecialchars($pesanan['total_harga']); ?>">
                                <?php
                                echo htmlspecialchars($pesanan['nama_pelanggan'] ?? 'Pesanan Laundry');
                                ?> (Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?>)
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <!-- Metode -->
                <div class="form-group">

                    <label>Metode Pembayaran</label>

                    <select name="metode" class="form-control" required>

                        <option value="">
                            -- Pilih Metode --
                        </option>

                        <option value="Tunai"
                            <?php echo isSelected('Tunai', old('metode')); ?>>
                            Tunai
                        </option>

                        <option value="Transfer"
                            <?php echo isSelected('Transfer', old('metode')); ?>>
                            Transfer
                        </option>

                    </select>

                </div>

                <!-- Jumlah -->
                <div class="form-group">

                    <label>Jumlah Dibayar</label>

                    <input
                        type="number"
                        id="jumlahDibayar"
                        name="jumlah_dibayar"
                        step="0.01"
                        class="form-control"
                        placeholder="Masukkan jumlah pembayaran"
                        value="<?php echo htmlspecialchars(old('jumlah_dibayar')); ?>"
                        required>

                </div>

                <!-- Bukti -->
                <div class="form-group">

                    <label>Bukti Pembayaran</label>

                    <input
                        type="text"
                        name="bukti"
                        class="form-control"
                        placeholder="Nama file atau detail transfer"
                        value="<?php echo htmlspecialchars(old('bukti')); ?>">

                </div>

            </div>

            <button type="submit" class="btn-save">
                Simpan Pembayaran
            </button>

        </form>

    </section>

    <!-- TABEL PEMBAYARAN -->
    <section class="content-card">

        <h2 class="section-title">
            Daftar Pembayaran
        </h2>

        <?php if (empty($_SESSION['pembayaran'])): ?>

            <div class="empty-data">
                Belum ada data pembayaran.
            </div>

        <?php else: ?>

            <div class="table-wrapper">

                <table>

                    <tr>
                        <th>Metode</th>
                        <th>Jumlah Dibayar</th>
                        <th>Bukti Pembayaran</th>
                    </tr>

                    <?php foreach ($_SESSION['pembayaran'] as $item): ?>

                        <tr>

                            <td>
                                <span class="badge-method">
                                    <?php echo htmlspecialchars($item['metode']); ?>
                                </span>
                            </td>

                            <td class="amount">
                                Rp <?php echo number_format($item['jumlah_dibayar'], 0, ',', '.'); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($item['bukti']); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

            </div>

        <?php endif; ?>

    </section>

</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>

<script>
    // Handle perubahan pemilihan pesanan
    document.getElementById('pesananSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const harga = selectedOption.getAttribute('data-harga');

        if (harga) {
            // Auto-fill jumlah pembayaran dengan harga pesanan
            document.getElementById('jumlahDibayar').value = harga;
        } else {
            // Reset jika tidak ada pesanan yang dipilih
            document.getElementById('jumlahDibayar').value = '';
        }
    });
</script>