<?php
require_once __DIR__ . '/../config.php';
$page_title = 'Pengeluaran';
$current_page = 'pengeluaran';

// Inisialisasi session pengeluaran jika belum ada
if (!isset($_SESSION['pengeluaran'])) {
    $_SESSION['pengeluaran'] = [];
}

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
        color: #2c3441;
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
        font-family: inherit;
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
        grid-column: 1 / -1;
    }

    .btn-save:hover {
        background: #0f172a;
        transform: translateY(-2px);
    }

    .btn-delete {
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        background: #ef4444;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .btn-delete:hover {
        background: #dc2626;
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
        font-weight: 600;
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

    .badge-keterangan {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        background: #dbeafe;
        color: #0c4a6e;
        font-size: 13px;
        font-weight: 600;
    }

    .amount {
        font-weight: 700;
        color: #0f172a;
    }

    .empty-data {
        padding: 40px 20px;
        text-align: center;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-size: 15px;
    }

    .summary-box {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .summary-card {
        background: #0c4a6e;
        padding: 20px;
        border-radius: 12px;
        color: #fff;
        text-align: center;
    }

    .summary-card h3 {
        font-size: 13px;
        font-weight: 600;
        margin: 0 0 8px 0;
        opacity: 0.9;
    }

    .summary-card .total {
        font-size: 24px;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .content-card {
            padding: 20px;
        }

        .section-title {
            font-size: 20px;
        }

        .form-layout {
            grid-template-columns: 1fr;
        }

        .btn-save {
            grid-column: 1;
        }

        table th,
        table td {
            padding: 12px 8px;
            font-size: 13px;
        }
    }
</style>

<div class="page-wrapper">

    <!-- FORM PENGELUARAN -->
    <section class="content-card">

        <h2 class="section-title">
            Form Pengeluaran
        </h2>

        <form method="post">

            <div class="form-layout">

                <!-- Tanggal Pengeluaran -->
                <div class="form-group">
                    <label>Tanggal Pengeluaran</label>
                    <input
                        type="date"
                        name="tanggal"
                        class="form-control"
                        value="<?php echo htmlspecialchars(old('tanggal')); ?>"
                        required>
                </div>

                <!-- Keterangan -->
                <div class="form-group">
                    <label>Keterangan</label>
                    <input
                        type="text"
                        name="keterangan"
                        class="form-control"
                        value="<?php echo htmlspecialchars(old('keterangan')); ?>"
                        placeholder="Deterjen, pewangi, bahan baku, dll"
                        required>
                </div>

                <!-- Jumlah Pengeluaran -->
                <div class="form-group">
                    <label>Jumlah Pengeluaran</label>
                    <input
                        type="number"
                        name="jumlah"
                        step="0.01"
                        class="form-control"
                        value="<?php echo htmlspecialchars(old('jumlah')); ?>"
                        placeholder="0"
                        required>
                </div>

            </div>

            <button type="submit" class="btn-save">
                Simpan Pengeluaran
            </button>

        </form>

    </section>

    <!-- TABEL PENGELUARAN -->
    <section class="content-card">

        <h2 class="section-title">
            Daftar Pengeluaran
        </h2>

        <?php if (empty($_SESSION['pengeluaran'])): ?>

            <div class="empty-data">
                📋 Belum ada data pengeluaran.
            </div>

        <?php else: ?>

            <!-- Summary Box -->
            <?php
            $total_pengeluaran = 0;
            foreach ($_SESSION['pengeluaran'] as $item) {
                $total_pengeluaran += (float)$item['jumlah'];
            }
            ?>
            <div class="summary-box">
                <div class="summary-card">
                    <h3>Total Pengeluaran</h3>
                    <div class="total">Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></div>
                </div>
            </div>

            <div class="table-wrapper" style="margin-top: 20px;">

                <table>

                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                    </tr>

                    <?php foreach ($_SESSION['pengeluaran'] as $item): ?>

                        <tr>

                            <td>
                                <?php
                                $tanggal = strtotime($item['tanggal']);
                                echo date('d/m/Y', $tanggal);
                                ?>
                            </td>

                            <td>
                                <span class="badge-keterangan">
                                    <?php echo htmlspecialchars($item['keterangan']); ?>
                                </span>
                            </td>

                            <td class="amount">
                                Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </table>

            </div>

        <?php endif; ?>

    </section>

</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>