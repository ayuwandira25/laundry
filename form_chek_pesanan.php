<?php
session_start();
$hide_sidebar = true;
require_once __DIR__ . '/header.php';

$q = trim($_GET['q'] ?? '');
$results = [];
$total_orders = 0;
$total_amount = 0;
$search_performed = false;
$error_message = '';
if ($q !== '') {
    $search_performed = true;
    $search = mysqli_real_escape_string($db, $q);
    $phone_search = preg_replace('/\D+/', '', $q);
    $phone_terms = [];

    if ($phone_search !== '') {
        $phone_terms[] = $phone_search;

        if (strpos($phone_search, '0') === 0) {
            $phone_terms[] = '62' . substr($phone_search, 1);
        } elseif (strpos($phone_search, '62') === 0) {
            $phone_terms[] = '0' . substr($phone_search, 2);
        }
    }

    $phone_column = "REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telepon, ' ', ''), '-', ''), '+', ''), '(', ''), ')', '')";
    $where_parts = ["nama LIKE '%$search%'", "telepon LIKE '%$search%'"];

    foreach (array_unique($phone_terms) as $phone_term) {
        $escaped_phone = mysqli_real_escape_string($db, $phone_term);
        $where_parts[] = "$phone_column LIKE '%$escaped_phone%'";
    }

    $customer_query = "SELECT id, nama, telepon FROM pelanggan WHERE " . implode(' OR ', $where_parts) . " ORDER BY nama";
    $customer_result = mysqli_query($db, $customer_query);

    if ($customer_result) {
        $customer_ids = [];
        while ($customer = mysqli_fetch_assoc($customer_result)) {
            $key = (int)$customer['id'];
            $customer_ids[] = $key;
            $results[$key] = [
                'pelanggan_nama' => $customer['nama'],
                'telepon' => $customer['telepon'],
                'orders' => [],
                'total' => 0,
                'selesai' => 0,
                'belum_selesai' => 0,
            ];
        }

        if (!empty($customer_ids)) {
            $ids = implode(',', array_map('intval', $customer_ids));
            $query = "SELECT p.*, pl.nama AS pelanggan_nama, pl.telepon AS pelanggan_telepon, l.jenis_layanan AS layanan_nama, l.kategori, l.harga_reguler, l.harga_express
                      FROM pesanan p
                      LEFT JOIN pelanggan pl ON p.pelanggan_id = pl.id
                      LEFT JOIN layanan l ON p.layanan_id = l.id
                      WHERE p.pelanggan_id IN ($ids)
                      ORDER BY p.tanggal_masuk DESC, p.id DESC";
            $result = mysqli_query($db, $query);
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $key = (int)$row['pelanggan_id'];
                    if (!isset($results[$key])) {
                        continue;
                    }

                    $results[$key]['orders'][] = $row;
                    $results[$key]['total'] += (float)$row['total_harga'];
                    if (strtolower($row['status']) === 'selesai') {
                        $results[$key]['selesai']++;
                    } else {
                        $results[$key]['belum_selesai']++;
                    }
                    $total_orders++;
                    $total_amount += (float)$row['total_harga'];
                }
            } else {
                $error_message = 'Terjadi kesalahan pencarian pesanan: ' . mysqli_error($db);
            }
        }
    } else {
        $error_message = 'Terjadi kesalahan pencarian pelanggan: ' . mysqli_error($db);
    }
}

function statusBadge($status)
{
    $status = trim((string)$status);
    $map = [
        'selesai' => 'badge-selesai',
        'diproses' => 'badge-proses',
        'proses' => 'badge-proses',
        'pending' => 'badge-proses',
        'siap' => 'badge-siap',
        'siap diambil' => 'badge-siap',
        'baru' => 'badge-baru',
    ];
    $labels = [
        'selesai' => 'Selesai',
        'diproses' => 'Diproses',
        'proses' => 'Dalam proses',
        'pending' => 'Menunggu diproses',
        'siap' => 'Siap diambil',
        'siap diambil' => 'Siap diambil',
        'baru' => 'Pesanan baru',
    ];
    $status_key = strtolower($status);
    $class = $map[$status_key] ?? 'badge-default';
    $text = $labels[$status_key] ?? ($status !== '' ? ucfirst($status) : 'Belum ada status');
    return '<span class="badge ' . htmlspecialchars($class) . '">' . htmlspecialchars($text) . '</span>';
}

function formatRp($value)
{
    return 'Rp ' . number_format((float)$value, 0, ',', '.');
}

function orderUnit($kategori)
{
    return strtolower((string)$kategori) === 'kiloan' ? 'kg' : 'pcs';
}

function initials($name)
{
    $parts = preg_split('/\s+/', trim($name));
    $letters = array_slice(array_map(function ($part) {
        return strtoupper(substr($part, 0, 1));
    }, $parts), 0, 2);
    return implode('', $letters);
}

?>

<section class="wrap">
    <div class="header">
        <div>
            <h1 class="page-title">Cek Pesanan</h1>
            <p class="page-subtitle">Temukan status pesanan dengan nama pelanggan atau nomor telepon.</p>
        </div>
        <span class="logo-badge">KykaLaundry</span>
    </div>

    <div class="search-box">
        <form method="get" action="form_chek_pesanan.php" class="search-row">
            <input
                type="text"
                name="q"
                id="searchInput"
                value="<?php echo htmlspecialchars($q); ?>"
                placeholder="Cari dengan nama atau nomor telepon"
                autocomplete="off"
                required />
            <button type="submit">Cari</button>
        </form>
        <p class="hint">Contoh: Sari, Budi, 08123456789</p>
    </div>

    <?php if ($error_message): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error_message); ?></div>
    <?php endif; ?>

    <?php if ($search_performed): ?>
        <?php if (empty($results)): ?>
            <div class="empty">
                <i class="ti ti-search-off" aria-hidden="true"></i>
                Tidak ditemukan pesanan untuk "<strong><?php echo htmlspecialchars($q); ?></strong>".
            </div>
        <?php else: ?>
            <div class="result-summary">
                <div class="summary-card">
                    <div class="s-num"><?php echo count($results); ?></div>
                    <div class="s-label">Pelanggan ditemukan</div>
                </div>
                <div class="summary-card">
                    <div class="s-num"><?php echo $total_orders; ?></div>
                    <div class="s-label">Riwayat pesanan</div>
                </div>
                <div class="summary-card">
                    <div class="s-num"><?php echo formatRp($total_amount); ?></div>
                    <div class="s-label">Nilai pesanan</div>
                </div>
            </div>

            <div class="result-section">
                <?php foreach ($results as $customer): ?>
                    <div class="customer-panel">
                        <div class="customer-head">
                            <div class="customer-info">
                                <div class="avatar"><?php echo htmlspecialchars(initials($customer['pelanggan_nama'])); ?></div>
                                <div>
                                    <div class="cname"><?php echo htmlspecialchars($customer['pelanggan_nama']); ?></div>
                                    <div class="cphone"><?php echo htmlspecialchars($customer['telepon']); ?></div>
                                </div>
                            </div>
                            <div class="customer-total">
                                <span>Total</span>
                                <strong><?php echo formatRp($customer['total']); ?></strong>
                            </div>
                        </div>

                        <div class="mini-stats">
                            <span><?php echo count($customer['orders']); ?> pesanan</span>
                            <span><?php echo $customer['selesai']; ?> selesai</span>
                            <span><?php echo $customer['belum_selesai']; ?> belum selesai</span>
                        </div>

                        <?php if (empty($customer['orders'])): ?>
                            <div class="empty-order">
                                Nomor telepon terdaftar, tetapi belum ada riwayat pesanan untuk pelanggan ini.
                            </div>
                        <?php else: ?>
                            <div class="table-wrap">
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Pesanan</th>
                                            <th>Tanggal</th>
                                            <th>Layanan</th>
                                            <th>Jumlah</th>
                                            <th>Jenis</th>
                                            <th>Catatan</th>
                                            <th>Status</th>
                                            <th>Total Harga</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $order_no = 1; foreach ($customer['orders'] as $order): ?>
                                            <tr>
                                                <td><?php echo $order_no++; ?></td>
                                                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                                <td><?php echo htmlspecialchars($order['tanggal_masuk']); ?></td>
                                                <td><?php echo htmlspecialchars($order['layanan_nama'] ?: 'Layanan tidak tersedia'); ?></td>
                                                <td><?php echo htmlspecialchars($order['jumlah'] . ' ' . orderUnit($order['kategori'])); ?></td>
                                                <td><?php echo htmlspecialchars(ucfirst($order['jenis_layanan'] ?: 'reguler')); ?></td>
                                                <td><?php echo htmlspecialchars($order['jenis_item'] ?: '-'); ?></td>
                                                <td><?php echo statusBadge($order['status']); ?></td>
                                                <td><strong><?php echo formatRp($order['total_harga']); ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<style>
    body.no-sidebar {
        background: #f4f4f4;
        color: #222;
    }

    body.no-sidebar header .topbar {
        background: #fff;
        color: #222;
        border-bottom: 1px solid #ddd;
        box-shadow: none;
    }

    body.no-sidebar main {
        max-width: 1180px;
    }

    body.no-sidebar section {
        box-shadow: none;
        border: 1px solid #e1e1e1;
    }

    .wrap {
        padding: 28px;
        max-width: 1120px;
        margin: 0 auto;
        background: #fff;
        border-radius: 8px;
    }

    .page-title {
        margin: 0;
        font-size: 26px;
        color: #222;
    }

    .page-subtitle {
        margin: 6px 0 0;
        color: #666;
        font-size: 14px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
    }

    .logo-badge {
        background: #f2f2f2;
        color: #333;
        border: 1px solid #d9d9d9;
        font-size: 13px;
        font-weight: 600;
        padding: 7px 12px;
        border-radius: 6px;
        margin-top: 4px;
    }

    .search-box {
        background: #fafafa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 18px;
    }

    .search-row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.75rem;
        align-items: center;
    }

    .search-row input {
        width: 100%;
        padding: 12px 14px;
        font-size: 15px;
        border: 1px solid #c8c8c8;
        border-radius: 6px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .search-row input:focus {
        border-color: #333;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.07);
    }

    .search-row button {
        padding: 12px 22px;
        border: none;
        border-radius: 6px;
        background: #333;
        color: white;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .search-row button:hover {
        background: #111;
    }

    .hint {
        margin: 12px 0 0;
        color: #777;
        font-size: 13px;
    }

    .result-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
        margin-top: 20px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
    }

    .s-num {
        font-size: 21px;
        font-weight: 700;
        color: #222;
    }

    .s-label {
        display: block;
        margin-top: 5px;
        font-size: 13px;
        color: #666;
    }

    .result-section {
        margin-top: 22px;
    }

    .customer-panel {
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        margin-bottom: 18px;
        overflow: hidden;
    }

    .customer-head {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: center;
        padding: 16px;
        border-bottom: 1px solid #e5e5e5;
        background: #fafafa;
    }

    .customer-info {
        display: flex;
        gap: 12px;
        align-items: center;
        min-width: 0;
    }

    .customer-total {
        text-align: right;
        white-space: nowrap;
    }

    .customer-total span {
        display: block;
        color: #777;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .customer-total strong {
        color: #222;
        font-size: 15px;
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 6px;
        background: #e9e9e9;
        color: #333;
        border: 1px solid #d8d8d8;
        display: grid;
        place-items: center;
        font-size: 14px;
        font-weight: 700;
        flex: 0 0 auto;
    }

    .cname {
        font-size: 16px;
        font-weight: 600;
        color: #222;
    }

    .cphone {
        font-size: 13px;
        color: #666;
    }

    .mini-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e5e5;
        color: #555;
        font-size: 13px;
    }

    .mini-stats span {
        background: #f6f6f6;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 6px 10px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 5px 9px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        border: 1px solid #d7d7d7;
        white-space: nowrap;
    }

    .badge-selesai {
        background: #efefef;
        color: #222;
    }

    .badge-proses {
        background: #fff;
        color: #444;
    }

    .badge-siap {
        background: #f7f7f7;
        color: #333;
    }

    .badge-baru {
        background: #f7f7f7;
        color: #333;
    }

    .badge-default {
        background: #f7f7f7;
        color: #333;
    }

    .table-wrap {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        min-width: 980px;
        border-collapse: collapse;
        margin: 0;
        font-size: 13px;
    }

    .history-table th,
    .history-table td {
        padding: 12px 14px;
        border: 0;
        border-bottom: 1px solid #e6e6e6;
        text-align: left;
        vertical-align: middle;
        color: #333;
        white-space: nowrap;
    }

    .history-table th {
        background: #f1f1f1;
        color: #222;
        font-size: 12px;
        font-weight: 700;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .history-table tbody tr:hover {
        background: #fafafa;
    }

    .empty-order {
        margin: 16px;
        padding: 14px;
        border: 1px dashed #bbb;
        border-radius: 8px;
        background: #fafafa;
        color: #666;
        font-size: 14px;
    }

    .empty {
        margin-top: 1.5rem;
        padding: 1.75rem 1.25rem;
        text-align: center;
        border: 1px dashed #bbb;
        border-radius: 8px;
        color: #666;
        background: #fafafa;
    }

    .empty i {
        display: block;
        font-size: 28px;
        margin-bottom: 0.75rem;
        color: #888;
    }

    .alert-error {
        margin-top: 1rem;
        padding: 1rem 1rem;
        border-radius: 8px;
        background: #f8f8f8;
        color: #333;
        border: 1px solid #ccc;
    }

    @media (max-width: 720px) {

        .result-summary,
        .summary-row,
        .search-row {
            grid-template-columns: 1fr;
        }

        .header {
            flex-direction: column;
            align-items: stretch;
        }

        .customer-head {
            align-items: flex-start;
            flex-direction: column;
        }

        .customer-total {
            text-align: left;
        }
    }
</style>

<?php require_once __DIR__ . '/footer.php'; ?>
