<?php
// Debug login dari form
require_once __DIR__ . '/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    echo "=== DEBUG LOGIN ===<br>";
    echo "Username input: $username<br>";
    echo "Password input: $password<br><br>";

    if (empty($username) || empty($password)) {
        $message = 'Username dan password harus diisi.';
    } else {
        $found_user = getUserForLogin($username);
        
        echo "Hasil getUserForLogin:<br>";
        var_dump($found_user);
        echo "<br><br>";

        if ($found_user) {
            echo "User ditemukan - Email: " . $found_user['email'] . ", Role: " . $found_user['role'] . "<br>";
            $verify = password_verify($password, $found_user['password']);
            echo "password_verify result: " . ($verify ? "TRUE" : "FALSE") . "<br>";
            
            if ($verify) {
                echo "<strong>LOGIN BERHASIL!</strong>";
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['user_name'] = $found_user['nama'];
                $_SESSION['user_role'] = $found_user['role'];
                echo "<br>Session diset: user_id=" . $_SESSION['user_id'] . ", role=" . $_SESSION['user_role'];
            } else {
                echo "<strong>Password TIDAK COCOK!</strong>";
            }
        } else {
            echo "User TIDAK ditemukan!<br>";
        }
        exit;
    }
}

echo "Gunakan form di bawah untuk test login:<br>";
?>
<form method="post" action="debug_login_form.php">
    <label>Username:</label><br>
    <input type="text" name="username" value="admin12@gmail.com"><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" value="123"><br><br>
    <button type="submit">Test Login</button>
</form>