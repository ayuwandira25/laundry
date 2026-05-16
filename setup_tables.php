<?php
$db = mysqli_connect("localhost", "root", "", "kykalaundry");
if (!$db) {
    echo "Koneksi gagal: " . mysqli_connect_error();
    exit;
}

// Read SQL file
$sql = file_get_contents("database.sql");

// Execute SQL statements (split by semicolon)
$statements = explode(";", $sql);
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (!empty($statement)) {
        if (mysqli_query($db, $statement)) {
            // Success
        }
    }
}

// Insert default admin user to register table
$hashed_password = password_hash("admin123", PASSWORD_DEFAULT);
$check = mysqli_query($db, "SELECT * FROM register WHERE email = 'admin@kyka.com'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($db, "INSERT INTO register (email, nama, jenis_kelamin, no_hp, alamat, password, role) 
        VALUES ('admin@kyka.com', 'Admin', 'Laki-laki', '081234567890', 'Jl. Admin No. 1', '$hashed_password', 'admin')");
    echo "User admin default ditambahkan ke tabel register";
} else {
    echo "Tabel sudah ada dan user admin sudah ada";
}

mysqli_close($db);