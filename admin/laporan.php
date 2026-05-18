<?php
require_once __DIR__ . '/../config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
$page_title = 'Laporan Keuangan';
$current_page = 'laporan';

$transaksi = getAll('transaksi');
$pengeluaran = getAll('pengeluaran');

// Debug: Cek apakah data tersedia
$debug_transaksi = !empty($transaksi);
$debug_pengeluaran = !empty($pengeluaran);

$total_pemasukan = array_sum(array_column($transaksi, 'total_biaya'));
$total_pengeluaran = array_sum(array_column($pengeluaran, 'jumlah'));
$laba = $total_pemasukan - $total_pengeluaran;

// Hitung jumlah transaksi dan pengeluaran
$jumlah_transaksi = count($transaksi);
$jumlah_pengeluaran = count($pengeluaran);

require_once __DIR__ . '/../layouts/admin_header.php';
?>

<style>
    .page-wrapper {
        padding: 20px;
    }

    .page-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 30px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 25px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card.income::before {
        background: linear-gradient(90deg, #10b981 0%, #059669 100%);
    }

    .stat-card.expense::before {
        background: linear-gradient(90deg, #ef4444 0%, #dc2626 100%);
    }

    .stat-card.profit::before {
        background: linear-gradient(90deg, #f59e0b 0%, #f97316 100%);
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .stat-amount {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .stat-info {
        font-size: 12px;
        color: #94a3b8;
    }

    .content-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.05);
        border: 1px solid #e5e7eb;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
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
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    table td {
        padding: 14px 15px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #334155;
    }

    table tr:hover {
        background: #f8fafc;
    }

    table tr:last-child td {
        border-bottom: none;
    }

    .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-info {
        background: #dbeafe;
        color: #0c4a6e;
    }

    .badge-success {
        background: #d1fae5;
        color: #065f46;
    }

    .amount {
        font-weight: 700;
        color: #0f172a;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #64748b;
    }

    .tools-bar {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .btn {
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-print {
        background: #6366f1;
        color: #fff;
    }

    .btn-print:hover {
        background: #4f46e5;
    }

    @media print {

        .tools-bar,
        .page-wrapper {
            display: none;
        }

        body {
            background: #fff;
        }

        .content-card {
            box-shadow: none;
            page-break-inside: avoid;
        }
    }

    @media (max-width: 768px) {
        .page-wrapper {
            padding: 15px;
        }

        .page-title {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-amount {
            font-size: 22px;
        }

        .content-card {
            padding: 20px;
        }

        table th,
        table td {
            padding: 10px 8px;
            font-size: 12px;
        }

        .tools-bar {
            gap: 8px;
        }

        .btn {
            padding: 8px 12px;
            font-size: 12px;
        }
    }
</style>

<div class="page-wrapper">

    <h1 class="page-title">📊 Laporan Keuangan</h1>

    <?php if (!$debug_transaksi && !$debug_pengeluaran): ?>
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 20px; margin-bottom: 20px; color: #dc2626;">
            <strong>⚠️ Perhatian:</strong> Database belum memiliki data transaksi atau pengeluaran. Silakan tambahkan data terlebih dahulu di menu Pesanan atau Pengeluaran.
        </div>
    <?php endif; ?>

    <!-- STATISTICS CARDS -->
    <div class="stats-grid">
        <div class="stat-card income">
            <div class="stat-label">Total Pemasukan</div>
            <div class="stat-amount">Rp <?php echo number_format($total_pemasukan, 0, ',', '.'); ?></div>
            <div class="stat-info">Dari <?php echo $jumlah_transaksi; ?> transaksi</div>
        </div>

        <div class="stat-card expense">
            <div class="stat-label">Total Pengeluaran</div>
            <div class="stat-amount">Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></div>
            <div class="stat-info">Dari <?php echo $jumlah_pengeluaran; ?> pengeluaran</div>
        </div>

        <div class="stat-card profit">
            <div class="stat-label">Laba/Rugi</div>
            <div class="stat-amount" style="color: <?php echo $laba >= 0 ? '#059669' : '#dc2626'; ?>">
                Rp <?php echo number_format($laba, 0, ',', '.'); ?>
            </div>
            <div class="stat-info">
                <?php echo $laba >= 0 ? '✓ Untung' : '✗ Rugi'; ?>
            </div>
        </div>
    </div>

    <!-- DETAIL PEMASUKAN -->
    <section class="content-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="section-title">Detail Pemasukan (Transaksi)</h2>
            <div class="tools-bar">
                <button class="btn btn-print" onclick="window.print()">🖨️ Cetak</button>
            </div>
        </div>

        <?php if (empty($transaksi)): ?>
            <div class="empty-state">
                📋 Belum ada data transaksi
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transaksi as $item): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-info">#<?php echo htmlspecialchars($item['id']); ?></span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($item['tanggal_masuk'])); ?></td>
                                <td><?php echo $item['tanggal_selesai'] ? date('d/m/Y', strtotime($item['tanggal_selesai'])) : '-'; ?></td>
                                <td>
                                    <span class="badge badge-success">Selesai</span>
                                </td>
                                <td class="amount">Rp <?php echo number_format($item['total_biaya'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- DETAIL PENGELUARAN -->
    <section class="content-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 class="section-title">Detail Pengeluaran</h2>
            <div class="tools-bar">
                <button class="btn btn-print" onclick="window.print()">🖨️ Cetak</button>
            </div>
        </div>

        <?php if (empty($pengeluaran)): ?>
            <div class="empty-state">
                📋 Belum ada data pengeluaran
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pengeluaran as $item): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-info">#<?php echo htmlspecialchars($item['id']); ?></span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($item['tanggal'])); ?></td>
                                <td><?php echo htmlspecialchars($item['keterangan']); ?></td>
                                <td class="amount">Rp <?php echo number_format($item['jumlah'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <!-- SUMMARY FOOTER -->
    <section class="content-card">
        <h2 class="section-title">Ringkasan Laporan</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
            <div style="padding: 15px; background: #f0fdf4; border-radius: 10px; border-left: 4px solid #10b981;">
                <div style="font-size: 12px; color: #065f46; font-weight: 600; margin-bottom: 8px;">TOTAL TRANSAKSI</div>
                <div style="font-size: 24px; font-weight: 700; color: #059669;"><?php echo $jumlah_transaksi; ?></div>
            </div>
            <div style="padding: 15px; background: #fef2f2; border-radius: 10px; border-left: 4px solid #ef4444;">
                <div style="font-size: 12px; color: #7f1d1d; font-weight: 600; margin-bottom: 8px;">TOTAL PENGELUARAN</div>
                <div style="font-size: 24px; font-weight: 700; color: #dc2626;"><?php echo $jumlah_pengeluaran; ?></div>
            </div>
            <div style="padding: 15px; background: #fffbeb; border-radius: 10px; border-left: 4px solid #f59e0b;">
                <div style="font-size: 12px; color: #92400e; font-weight: 600; margin-bottom: 8px;">RATA-RATA PEMASUKAN</div>
                <div style="font-size: 24px; font-weight: 700; color: #f97316;">
                    Rp <?php echo $jumlah_transaksi > 0 ? number_format($total_pemasukan / $jumlah_transaksi, 0, ',', '.') : '0'; ?>
                </div>
            </div>
        </div>
    </section>

</div>

<?php require_once __DIR__ . '/../layouts/admin_footer.php'; ?>