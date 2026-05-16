<?php
// Test login langsung
require_once __DIR__ . '/config.php';

$username = 'admin12@gmail.com';
$password = '123';

$found_user = getUserForLogin($username);

echo "User ditemukan: " . ($found_user ? "YA" : "TIDAK") . "<br>";
if ($found_user) {
    echo "Email: " . $found_user['email'] . "<br>";
    echo "Password di DB: " . $found_user['password'] . "<br>";
    $verify = password_verify($password, $found_user['password']);
    echo "Password cocok: " . ($verify ? "YA" : "TIDAK") . "<br>";
}