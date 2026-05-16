<?php
// Debug login - cek apa yang terjadi
require_once __DIR__ . '/config.php';

$username = 'admin12@gmail.com';
$password = '123';

echo "Mencari user dengan username: $username<br>";

// Cek langsung di database
$result = mysqli_query($db, "SELECT * FROM register WHERE email = '$username' OR nama = '$username'");

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    echo "User ditemukan!<br>";
    echo "Email: " . $user['email'] . "<br>";
    echo "Nama: " . $user['nama'] . "<br>";
    echo "Role: " . $user['role'] . "<br>";
    echo "Password di DB: " . substr($user['password'], 0, 30) . "...<br>";
    
    if (password_verify($password, $user['password'])) {
        echo "<strong>Password COCOK!</strong>";
    } else {
        echo "<strong>Password TIDAK COCOK!</strong>";
        echo "<br>Menggunakan password_hash baru...";
        $new_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($db, "UPDATE register SET password = '$new_hash' WHERE email = '$username'");
        echo "<br>Password diupdate ulang.";
    }
} else {
    echo "User TIDAK ditemukan di database!";
    
    // Cek semua user di tabel register
    echo "<br><br>Cek semua user di tabel register:<br>";
    $all = mysqli_query($db, "SELECT * FROM register");
    while ($row = mysqli_fetch_assoc($all)) {
        echo "- Email: " . $row['email'] . ", Role: " . $row['role'] . "<br>";
    }
}