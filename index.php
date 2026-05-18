<?php
session_start();

// Jika user sudah login sebagai admin
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Jika user sudah login (tapi bukan admin)
if (isset($_SESSION['user_id'])) {
    header('Location: user_dashboard.php');
    exit;
}

// Jika user belum login, tampilkan halaman informasi/landing page
header('Location: informasi.php');
exit;
