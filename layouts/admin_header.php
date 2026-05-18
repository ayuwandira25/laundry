<?php
require_once __DIR__ . '/../config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$is_admin = $_SESSION['user_role'] === 'admin';
$page_title = $page_title ?? 'Dashboard';
$current_page = $current_page ?? 'dashboard';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> - Kyka Laundry</title>

    <!-- AdminLTE 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/css/adminlte.min.css">

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .app-header {
            background: #1e293b;
        }

        .brand-text {
            font-weight: 600;
        }

        .sidebar-wrapper {
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
        }

        .nav-sidebar .nav-link {
            color: #334155;
            border-radius: 10px;
            margin-bottom: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-sidebar .nav-link:hover {
            background: #e2e8f0;
            color: #0f172a;
            padding-left: calc(0.5rem + 4px);
        }

        .nav-sidebar .nav-link.active {
            background: #cbd5e1;
            color: #1e293b;
            font-weight: 600;
            border-left: 4px solid #1e293b;
            padding-left: calc(0.5rem - 4px);
        }

        .content-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
        }

        .small-box {
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
            transition: 0.3s;
            border: none;
        }

        .small-box:hover {
            transform: translateY(-5px);
        }

        .small-box .inner h3 {
            font-size: 32px;
            font-weight: bold;
        }

        .small-box .inner p {
            font-size: 15px;
        }

        .small-box .icon {
            top: 15px;
            font-size: 60px;
            opacity: 0.15;
        }

        .bg-soft-dark {
            background: #1e293b;
            color: #fff;
        }

        .bg-soft-blue {
            background: #334155;
            color: #fff;
        }

        .bg-soft-gray {
            background: #475569;
            color: #fff;
        }

        .bg-soft-light {
            background: #64748b;
            color: #fff;
        }

        .welcome-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .welcome-card h2 {
            font-weight: 700;
            color: #1e293b;
        }

        .welcome-card p {
            color: #64748b;
            margin-top: 10px;
        }

        .main-footer {
            background: #fff;
            border-top: 1px solid #e5e7eb;
            color: #64748b;
        }

        .user-panel {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 15px;
        }

        .brand-link {
            border-bottom: 1px solid #e5e7eb;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        <!-- Navbar -->
        <nav class="app-header navbar navbar-expand bg-dark navbar-dark">
            <div class="container-fluid">

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">
                            <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../logout.php' : 'logout.php'; ?>" class="nav-link">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>

            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="app-sidebar shadow">

            <div class="sidebar-brand">
                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../dashboard.php' : 'dashboard.php'; ?>" class="brand-link text-decoration-none">
                    <span class="brand-text fw-light">
                        Kyka Laundry
                    </span>
                </a>
            </div>

            <div class="sidebar-wrapper">

                <nav class="mt-3">

                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview">

                        <li class="nav-item">
                            <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? '../dashboard.php' : 'dashboard.php'; ?>" class="nav-link <?php echo ($current_page === 'dashboard') ? 'active' : ''; ?>">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <?php if ($is_admin): ?>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './pelanggan.php' : 'admin/pelanggan.php'; ?>" class="nav-link <?php echo ($current_page === 'pelanggan') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Pelanggan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './karyawan.php' : 'admin/karyawan.php'; ?>" class="nav-link <?php echo ($current_page === 'karyawan') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-user-tie"></i>
                                    <p>Karyawan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './layanan.php' : 'admin/layanan.php'; ?>" class="nav-link <?php echo ($current_page === 'layanan') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-soap"></i>
                                    <p>Layanan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './pesanan.php' : 'admin/pesanan.php'; ?>" class="nav-link <?php echo ($current_page === 'pesanan') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-clipboard-list"></i>
                                    <p>Pesanan</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './status.php' : 'admin/status.php'; ?>" class="nav-link <?php echo ($current_page === 'status') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-check-circle"></i>
                                    <p>Status Cucian</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './pembayaran.php' : 'admin/pembayaran.php'; ?>" class="nav-link <?php echo ($current_page === 'pembayaran') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-credit-card"></i>
                                    <p>Pembayaran</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './pengeluaran.php' : 'admin/pengeluaran.php'; ?>" class="nav-link <?php echo ($current_page === 'pengeluaran') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-wallet"></i>
                                    <p>Pengeluaran</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './gaji.php' : 'admin/gaji.php'; ?>" class="nav-link <?php echo ($current_page === 'gaji') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-money-bill-wave"></i>
                                    <p>Gaji</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?php echo strpos($_SERVER['REQUEST_URI'], '/admin/') !== false ? './laporan.php' : 'admin/laporan.php'; ?>" class="nav-link <?php echo ($current_page === 'laporan') ? 'active' : ''; ?>">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>Laporan</p>
                                </a>
                            </li>

                        <?php endif; ?>

                    </ul>

                </nav>

            </div>

        </aside>

        <!-- Main Content -->
        <main class="app-main">

            <div class="app-content-header">
                <div class="container-fluid">

                    <div class="content-header">
                        <h1><?php echo htmlspecialchars($page_title); ?></h1>
                    </div>

                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">