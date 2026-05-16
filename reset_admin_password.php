<?php
// Jalankan file ini sekali saja untuk memperbaiki password admin
require_once __DIR__ . '/config.php';

$new_password = password_hash('admin123', PASSWORD_DEFAULT);
$sql = "UPDATE register SET password = '$new_password' WHERE email = 'admin@kyka.com' AND role = 'admin'";
if (mysqli_query($db, $sql)) {
    echo "Password admin berhasil direset.";
} else {
    echo "Gagal reset password admin: " . mysqli_error($db);
}
