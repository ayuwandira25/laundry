<?php
require_once __DIR__ . '/config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $no_hp = $_POST['no_hp'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $role = $_POST['role'] ?? 'pelanggan';
    $admin_code = $_POST['admin_code'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if ($password !== $password_confirm) {
        $message = 'Password dan konfirmasi password tidak cocok.';
    } elseif (empty($email) || empty($nama) || empty($jenis_kelamin) || empty($no_hp) || empty($alamat) || empty($password)) {
        $message = 'Semua field harus diisi.';
    } elseif ($role === 'admin' && $admin_code !== 'kykaadmin2026') {
        $message = 'Kode admin salah.';
    } else {
        // Check if email already exists in register table
        if (checkEmailExists($email)) {
            $message = 'Registrasi gagal. Email sudah terdaftar.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $data = [
                'email' => $email,
                'nama' => $nama,
                'jenis_kelamin' => $jenis_kelamin,
                'no_hp' => $no_hp,
                'alamat' => $alamat,
                'password' => $hashed_password,
                'role' => $role,
            ];
            if (registerUser($data)) {
                header('Location: login.php?success=1');
                exit;
            } else {
                $message = 'Registrasi gagal. Silakan coba lagi.';
            }
        }
    }
}

require_once __DIR__ . '/header.php';
?>
<section class="register-card">
    <h2>Register</h2>
    <?php if ($message): ?>
        <p style="color: red; text-align: center;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="#">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
            <option value="">Pilih jenis kelamin</option>
            <option value="Laki-laki">Laki-laki</option>
            <option value="Perempuan">Perempuan</option>
        </select>

        <label>No HP</label>
        <input type="text" name="no_hp" required>

        <label>Alamat</label>
        <textarea name="alamat" rows="3" required></textarea>

        <label>Role</label>
        <div style="display: flex; gap: 20px; margin-bottom: 16px;">
            <label><input type="radio" name="role" value="pelanggan" checked> Pelanggan</label>
            <label><input type="radio" name="role" value="admin"> Admin</label>
        </div>

        <div id="admin-code" style="display: none;">
            <label>Kode Admin (khusus pemilik)</label>
            <input type="password" name="admin_code">
        </div>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirm" required>

        <button type="submit">Daftar</button>
    </form>
    <p class="register-footer">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</section>
<script>
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const adminCode = document.getElementById('admin-code');
        if (this.value === 'admin') {
            adminCode.style.display = 'block';
        } else {
            adminCode.style.display = 'none';
        }
    });
});
</script>
<?php require_once __DIR__ . '/footer.php'; ?>