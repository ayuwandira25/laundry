<?php
$db = mysqli_connect("localhost", "root", "");
if (!$db) {
    echo "Koneksi gagal: " . mysqli_connect_error();
    exit;
}
if (mysqli_query($db, "CREATE DATABASE IF NOT EXISTS kykalaundry")) {
    echo "Database kykalaundry dibuat/sudah ada";
} else {
    echo "Gagal membuat database: " . mysqli_error($db);
}
mysqli_close($db);