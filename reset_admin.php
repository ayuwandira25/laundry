<?php
// Reset admin user dengan username dan password baru
require_once __DIR__ . '/config.php';

$email = 'admin12@gmail.com';
$password = password_hash('123', PASSWORD_DEFAULT);

// Cek apakah email sudah ada, jika ya update, jika tidak insert
$check = mysqli_query($db, "SELECT * FROM register WHERE email = '$email'");

if (mysqli_num_rows($check) > 0) {
    $sql = "UPDATE register SET password = '$password', role = 'admin', nama = 'Admin' WHERE email = '$email'";
} else {
    $sql = "INSERT INTO register (email, nama, jenis_kelamin, no_hp, alamat, password, role) 
            VALUES ('$email', 'Admin', 'Laki-laki', '081234567890', 'Jl. Admin No. 1', '$password', 'admin')";
}

if (mysqli_query($db, $sql)) {
    echo "Admin user berhasil dibuat/diperbarui.";
    echo "<br>Email: $email";
    echo "<br>Password: 123";
} else {
    echo "Gagal: " . mysqli_error($db);
}